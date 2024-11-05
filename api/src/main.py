from fastapi import FastAPI
import mysql.connector
import os
import glob
import logging
import time

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI()

def wait_for_mysql():
    logger.info("Waiting for MySQL to be ready...")
    while True:
        try:
            conn = mysql.connector.connect(
                host="mysql",
                user=os.environ["MYSQL_USER"],
                password=os.environ["MYSQL_PASSWORD"],
                database=os.environ["MYSQL_DATABASE"]
            )
            conn.close()
            logger.info("MySQL is ready!")
            break
        except mysql.connector.Error as err:
            time.sleep(5)

def run_migrations():
    conn = None
    try:
        wait_for_mysql()

        conn = mysql.connector.connect(
            host="mysql",
            user=os.environ["MYSQL_USER"],
            password=os.environ["MYSQL_PASSWORD"],
            database=os.environ["MYSQL_DATABASE"]
        )
        cursor = conn.cursor()

        migration_folder = os.path.join(os.path.dirname(__file__), '../database/migrations')
        migration_files = sorted(glob.glob(os.path.join(migration_folder, '*.sql')))

        for filename in migration_files:
            try:
                logger.info(f"Applying migration: {filename}")
                with open(filename, 'r') as file:
                    sql_script = file.read()
                    
                    for statement in sql_script.split(';'):
                        statement = statement.strip()
                        if statement:
                            cursor.execute(statement)
                logger.info(f"Migration {filename} applied successfully.")
            except Exception as e:
                logger.error(f"Error applying migration {filename}: {e}")
                conn.rollback()
                break

        conn.commit()
    except mysql.connector.Error as err:
        logger.error(f"Database connection error: {err}")
    finally:
        if conn and conn.is_connected():
            cursor.close()
            conn.close()
            logger.info("Database connection closed.")

@app.on_event("startup")
async def startup():
    run_migrations()

@app.get("/")
async def root():
    return {"message": "Hello World"}

if __name__ == "__main__":
    run_migrations()
