class UserModel:
    def __init__(self, username: str, email: str, password: str):
        self.username = username
        self.password = password
        self.email = email

    @classmethod
    def from_dict(cls, data: dict):
        try:
            name = data["name"]
            password = data["password"]
            email = data["email"]
        except KeyError as e:
            raise ValueError(f"Missing field: {e}")

        if not isinstance(name, str) or not name:
            raise ValueError("Username must be a non-empty string")
        if not isinstance(password, str) or not password:
            raise ValueError("Password must be a non-empty string")
        if email and (not isinstance(email, str) or "@" not in email):
            raise ValueError("Invalid email format")
        
        return cls(name, email, password)

    def to_dict(self):
        return {"username": self.username, "email": self.email}