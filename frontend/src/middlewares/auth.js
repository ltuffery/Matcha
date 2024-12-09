import { isAuthenticated } from "@/services/auth";

export const authGuard = (to, from, next) => {
  if (isAuthenticated()) {
    next();
  } else {
    next({ name: 'home' });
  }
}