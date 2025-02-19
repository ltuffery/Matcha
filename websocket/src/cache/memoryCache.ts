export class MemoryCache {
  private static _cache: Map<string, any> = new Map();

  static set(key: string, value: any): void {
    MemoryCache._cache.set(key, value);
  }

  static get(key: string): any | undefined {
    return MemoryCache._cache.get(key);
  }

  static delete(key: string): void {
    MemoryCache._cache.delete(key);
  }

  static clear(): void {
    MemoryCache._cache.clear();
  }
}
