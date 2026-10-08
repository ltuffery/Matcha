function requireEnv(name: string): string {
  const value = process.env[name];
  if (!value) {
    throw new Error(`Missing required env var: ${name}`);
  }
  return value;
}

export const config = {
  port: Number(process.env.PORT ?? 4000),
  frontOrigin: process.env.FRONT_ORIGIN ?? "http://localhost:5173",
  redisUrl: process.env.REDIS_URL ?? "redis://redis:6379",

  jwtSecret: requireEnv("JWT_SECRET"),
  internalSecret: requireEnv("INTERNAL_SECRET"),
};