import * as process from "node:process";
import {createConnection} from "mysql2";

export const db = createConnection({
  host: "mysql",
  user: process.env.MYSQL_USER,
  password: process.env.MYSQL_PASSWORD,
  database: process.env.MYSQL_DATABASE,
})
