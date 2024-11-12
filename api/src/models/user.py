import os
import mysql.connector
import bcrypt

import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class UserModel:
    def __init__(self, username: str, email: str, password: str):
        self.username = username
        self.password = bcrypt.hashpw(password, bcrypt.gensalt())
        self.email = email

    @classmethod
    def from_dict(cls, data: dict):
        try:
            username = data["username"]
            password = data["password"]
            email = data["email"]
        except KeyError as e:
            raise ValueError(f"Missing field: {e}")

        if not isinstance(username, str) or not username:
            raise ValueError("Username must be a non-empty string")
        if not isinstance(password, str) or not password:
            raise ValueError("Password must be a non-empty string")
        if email and (not isinstance(email, str) or "@" not in email):
            raise ValueError("Invalid email format")
        
        return cls(username, email, password)

    def to_dict(self):
        return {"username": self.username, "email": self.email}

    def save(self):
        db = mysql.connector.connect(
                host="mysql",
                user=os.environ["MYSQL_USER"],
                password=os.environ["MYSQL_PASSWORD"],
                database=os.environ["MYSQL_DATABASE"]
            )
        sql = "INSERT INTO users (`username`, `email`, `password`) VALUES (%s, %s, %s)"

        cursor = db.cursor()
        cursor.execute(sql, (self.username, self.email, self.password))
        db.commit()
        cursor.close()
        db.close()