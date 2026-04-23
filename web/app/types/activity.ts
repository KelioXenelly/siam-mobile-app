export type Activity = {
  id: number;
  action: string;
  detail: string;
  time: string;
  status: "success" | "warning" | "info" | "danger";
};
