import mysql.connector
import os
import glob
import logging
import time

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def get_db():
    return mysql.connector.connect(
        host="mysql",
        user=os.environ["MYSQL_USER"],
        password=os.environ["MYSQL_PASSWORD"],
        database=os.environ["MYSQL_DATABASE"]
    )

def migrations_table_exist(cursor) -> bool:
    cursor.execute("SHOW TABLES LIKE 'migrations'")
    return bool(cursor.fetchone())

def register_migration(cursor, filename: str):
    sql = "INSERT INTO migrations (migration) VALUES (%s)"

    cursor.execute(sql, (filename,))

def migration_exist(cursor, migration: str):
    sql = "SELECT * FROM migrations WHERE migration=%s"

    cursor.execute(sql, (migration,))

    return len(cursor.fetchall()) > 0

def wait_for_mysql():
    logger.info("Waiting for MySQL to be ready...")
    while True:
        try:
            conn = get_db()
            conn.close()
            logger.info("MySQL is ready!")
            break
        except mysql.connector.Error as err:
            time.sleep(5)

def apply_migration():
    conn = None

    try:
        wait_for_mysql()

        conn = get_db()
        cursor = conn.cursor()

        migration_folder = os.path.join(os.path.dirname(__file__), '../database/migrations')
        migration_files = sorted(glob.glob(os.path.join(migration_folder, '*.sql')))

        for filename in migration_files:
            if migrations_table_exist(cursor) and migration_exist(cursor, filename):
                continue

            try:
                logger.info(f"Applying migration: {filename}")

                with open(filename, 'r') as file:
                    sql_script = file.read()
                    
                    for statement in sql_script.split(';'):
                        statement = statement.strip()
                        if statement:
                            cursor.execute(statement)

                if migrations_table_exist(cursor):
                    register_migration(cursor, filename)

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