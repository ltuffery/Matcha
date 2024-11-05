from fastapi import FastAPI
from .migrate import apply_migration

app = FastAPI()

@app.on_event("startup")
async def startup():
    apply_migration()

@app.get("/")
async def root():
    return {"message": "Hello World"}

if __name__ == "__main__":
    apply_migration()
