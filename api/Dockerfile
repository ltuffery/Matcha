FROM python:3.9

WORKDIR /api

COPY requirements.txt requirements.txt

RUN pip install --no-cache-dir --upgrade -r /api/requirements.txt

CMD ["fastapi", "run", "src/main.py", "--port", "3000"]