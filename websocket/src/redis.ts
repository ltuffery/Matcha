import { RedisClient } from "bun";

async function main() {
  // client personnalisé
  const client = new RedisClient("redis://redis:6379");

  await client.set("counter", "0");
  await client.incr("counter");

  console.log(await client.get("counter"));
}

main();
