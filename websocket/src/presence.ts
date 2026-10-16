type UserId = string;
type Status = "online" | "offline";
type Notifier = (to: UserId, payload: { userId: UserId; status: Status }) => void;

interface PresenceState {
  connections: Map<UserId, Set<string>>;
  subscribers: Map<UserId, Set<UserId>>;   // B -> Follow B
  subscriptions: Map<UserId, Set<UserId>>; // A -> A Follow
  offlineTimers: Map<UserId, Timer>;
}

// Hot reload bun
const g = globalThis as typeof globalThis & { __presence?: PresenceState };
const state: PresenceState = (g.__presence ??= {
  connections: new Map(),
  subscribers: new Map(),
  subscriptions: new Map(),
  offlineTimers: new Map(),
});

const MAX_SUBS = 100;
const GRACE_MS = 5000;

let notifier: Notifier = () => {};
export function setNotifier(fn: Notifier) {
  notifier = fn;
}

export const isOnline = (id: UserId) => (state.connections.get(id)?.size ?? 0) > 0;

function notify(userId: UserId, status: Status) {
  for (const sub of state.subscribers.get(userId) ?? []) {
    notifier(sub, { userId, status });
  }
}

export function addConnection(userId: UserId, connId: string) {
  const timer = state.offlineTimers.get(userId);
  const pendingOffline = timer !== undefined;
  if (timer) clearTimeout(timer);
  state.offlineTimers.delete(userId);

  const wasOnline = isOnline(userId);
  let set = state.connections.get(userId);
  if (!set) state.connections.set(userId, (set = new Set()));
  set.add(connId);

  if (!wasOnline && !pendingOffline) notify(userId, "online");
}

export function removeConnection(userId: UserId, connId: string) {
  state.connections.get(userId)?.delete(connId);
  if (isOnline(userId)) return;

  state.connections.delete(userId);
  for (const t of state.subscriptions.get(userId) ?? []) {
    state.subscribers.get(t)?.delete(userId);
  }
  state.subscriptions.delete(userId);

  state.offlineTimers.set(
    userId,
    setTimeout(() => {
      state.offlineTimers.delete(userId);
      if (!isOnline(userId)) notify(userId, "offline");
    }, GRACE_MS),
  );
}

export type SubscribeResult =
  | { ok: true; status: Status }
  | { ok: false; error: "too_many_subscriptions" };

export function subscribe(me: UserId, target: UserId): SubscribeResult {
  let mine = state.subscriptions.get(me);
  if (!mine) state.subscriptions.set(me, (mine = new Set()));
  if (mine.size >= MAX_SUBS && !mine.has(target)) {
    return { ok: false, error: "too_many_subscriptions" };
  }
  mine.add(target);

  let subs = state.subscribers.get(target);
  if (!subs) state.subscribers.set(target, (subs = new Set()));
  subs.add(me);

  return { ok: true, status: isOnline(target) ? "online" : "offline" };
}

export function unsubscribe(me: UserId, target: UserId) {
  state.subscriptions.get(me)?.delete(target);
  state.subscribers.get(target)?.delete(me);
}
