from fastapi import FastAPI, Request, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from .migrate import apply_migration
from .models.user import UserModel
import json

app = FastAPI()

origins = [
    "*",
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.on_event("startup")
async def startup():
    apply_migration()

@app.get("/")
async def root():
    return {"message": "Hello World"}

@app.post("/auth/register")
async def user_register(request: Request):
    try:
        body = await request.json()
        user = UserModel.from_dict(body)
        return {"status": "success", "user": user.to_dict()}
    except (json.JSONDecodeError, ValueError) as e:
        raise HTTPException(status_code=400, detail=str(e))

if __name__ == "__main__":
    apply_migration()
