# Project management with clients (Monday.com–style)

## Goal

- **Todo list per project** — Each project has tasks (todos); admins can add, edit, complete, reorder, assign, set due dates.
- **Todo list per client** — When a client has open projects, show one place with all tasks across those projects (client-level view).
- **Project management** — Projects have status, dates, notes; tasks have title, status, assignee, due date, priority; optional board (kanban) view later.

---

## Current state

- **Clients** — List, add/edit, delete. No “client view” page yet.
- **Projects** — List, add/edit, delete. Fields: name, client_id, status (lead / in_progress / done), started_at, ended_at, notes. No project “view” page; list links straight to edit form.
- **Quotes / Invoices** — Can link to client and optional project.

---

## Data model (new)

### `project_tasks` table

| Column         | Type           | Description |
|----------------|----------------|-------------|
| id             | INTEGER PK     | |
| project_id     | INTEGER NOT NULL| FK → projects(id) ON DELETE CASCADE |
| title          | VARCHAR(255)   | Task title |
| description    | TEXT           | Optional details |
| status         | VARCHAR(32)    | todo, in_progress, done (default: todo) |
| sort_order     | INTEGER        | For ordering in list/board (default 0) |
| due_date       | DATE           | Optional |
| priority       | VARCHAR(16)    | low, medium, high (optional) |
| assigned_to    | INTEGER        | FK → admin_users(id), nullable |
| completed_at   | DATETIME       | Set when status → done |
| client_action  | INTEGER        | 1 = “Customer to do”, shown on client dashboard (default 0) |
| created_at     | DATETIME       | |
| updated_at     | DATETIME       | |

### `projects` (extended)

- **closed_at** — DATETIME, set when status becomes “done” (Closed).

### `clients` (extended)

- **portal_enabled** — INTEGER 0/1. When 1, client can access dashboard with token.
- **portal_token** — VARCHAR(64) UNIQUE. Secret token for `/client-dashboard.php?token=...`.

---

## Implementation todo list

### Phase 1 — Tasks per project (foundation) ✅

- [x] **1.1** Add `project_tasks` table in `config/database.php` (init_db).
- [x] **1.2** Create **project view** page (`project-view.php`): show project info (name, client, status, dates, notes), linked quotes/invoices, and **task list** (add / edit / delete / mark complete). Link from projects list to “View” and “Edit”.
- [x] **1.3** Task actions: add task (title, optional description, status, due_date, priority, assigned_to), edit task, delete task, mark complete (status → done, completed_at).
- [x] **1.4** Activity log: log task created, updated, completed, deleted.

### Phase 2 — Todo list per client ✅

- [x] **2.1** Create **client view** page (`client-view.php`): client info, their projects (with status), and **aggregated todo list** — all tasks from projects where status is not `done` (open projects), flat list with project name link. Read-only for viewer; edit via link to project for admin/editor.
- [x] **2.2** Clients list: link client name to `client-view.php`; add “View” and “Edit” / “Delete” in actions.
- [x] **2.3** “Open todos” count or badge per client in clients list (e.g. “3 tasks”); links to client view #todo.

### Phase 3 — Assignee, due date, priority ✅

- [x] **3.1** In project view (and task form): assignee dropdown (admin users), due date, priority (low/medium/high). Shown in task list and in client todo list.
- [x] **3.2** Activity log: entity type `project_task` in Activity page.

### Phase 4 — UX and views (optional / later)

- [x] **4.1** **Kanban board** — Project view: “List” | “Board” toggle. Board shows three columns (To do, In progress, Done); move via “→ In progress” / “→ Done” etc. (task-status.php). Edit/Cancel preserve view.
- [x] **4.2** **Filters** — Project view: filter tasks by assignee, due from/to, priority. Form submits GET; “Clear filters” link.
- [x] **4.3** **Subtask / checklist** — Table `project_task_items` (project_task_id, title, is_done, sort_order). In task edit: checklist rows (title + Done); list/board show “done/total”.
- [x] **4.4** **Client portal** — Implemented. Admin enables per client (client form); client opens `/client-dashboard.php?token=...`. Shows: Your to-do (tasks with `client_action=1`), Projects, Quotes & invoices. Access only when `portal_enabled=1`.

### Phase 5 — Reporting (optional)

- [x] **5.1** Dashboard widget: “My tasks” — tasks assigned to current user (not done), up to 15, with project name and due date (overdue in red). Link to project view with task in edit mode.
- [x] **5.2** **Task report** — `task-report.php`: filter by project, client, assignee, status, due from/to; table of tasks; “Export CSV” button.

---

## File checklist

| File | Purpose |
|------|--------|
| `config/database.php` | Add `project_tasks` table in init_db(). |
| `public/admin/project-view.php` | Project detail + task list + add/edit/complete/delete task. |
| `public/admin/task-form.php` or inline form | Add/edit task (title, description, status, due_date, priority, assigned_to). |
| `public/admin/task-delete.php` | DELETE task, redirect back to project-view. |
| `public/admin/task-complete.php` | Set status=done, completed_at; redirect to project-view. |
| `public/admin/client-view.php` | Client detail + open projects + aggregated todo list. |
| `public/admin/projects.php` | Add link “View” to project-view; keep “Edit” / “Delete”. |
| `public/admin/clients.php` | Link client name to client-view; keep “Edit” / “Delete”. |
| `config/activity.php` | Already has log_activity(); use for task actions. |
| `public/admin/includes/sidebar.php` | No change (Projects already there). |
| `public/client-dashboard.php` | Client portal: token-only access; Your to-do, Projects, Quotes & invoices. |
| `public/admin/task-report.php` | Task report: filters, table, Export CSV. Sidebar: “Task report”. |
| `config/database.php` | `project_status_label()`, `projects.closed_at`, `clients.portal_*`, `project_tasks.client_action`, `project_task_items` table. |

---

## Status flow

- **Project status:** lead → in_progress → on_hold → done (displayed as “Closed”). When set to Closed, `closed_at` is set. Only “open” projects (status ≠ done) count for client todo list and open-task count.
- **Task status:** todo → in_progress → done. “Done” tasks shown with strikethrough / in Done column.
- **Customer to-do:** Tasks with `client_action=1` appear on the client dashboard under “Your to-do” (and show a “Customer” badge in admin).

---

## Permissions

- **Viewer:** Can open project-view and client-view; see tasks; no add/edit/delete task, no edit project.
- **Editor / Admin:** Full task and project edit (as today). Client view: can edit tasks from here or via project view.

---

*Document for Systemiks project management. Implement in order; Phase 1–3 give “todo per project” and “todo per client” with assignee and due date.*
