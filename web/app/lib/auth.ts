export function getToken(): string | null {
  return localStorage.getItem("token");
}

export function isAuthenticated(): boolean {
  return !!getToken();
}

export function logout(): void {
  localStorage.removeItem("token");
}

export function setToken(token: string): void {
  localStorage.setItem("token", token);
}