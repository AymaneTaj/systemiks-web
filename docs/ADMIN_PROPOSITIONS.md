# Systemiks Admin — Verification & Propositions

## ✅ Admin dashboard — verification (complete & working)

| Area | Status | Notes |
|------|--------|--------|
| **Auth** | ✅ | Login, logout, session, redirect to login when not authenticated. All admin pages use `admin_require_login()`. |
| **Dashboard** | ✅ | Stats (clients, quotes, invoices, leads), cards with links to Site, Pages, Scheduling, Settings. |
| **Clients** | ✅ | List, Add/Edit form, Delete. Used by quotes/invoices. |
| **Projects** | ✅ | List, Add/Edit (client, status, dates), Delete. Optional link on quotes/invoices. |
| **Quotes** | ✅ | List, Create/Edit (template + CAD), View/Print, Convert to invoice. |
| **Invoices** | ✅ | List, Create/Edit, View/Print, Mark paid. Can be created from quote. |
| **Leads** | ✅ | List, View (status + notes), Convert to quote (creates/finds client + draft quote). |
| **Settings** | ✅ | Company info + quote/invoice defaults (prefixes, validity, payment terms). |
| **Contact → Leads** | ✅ | `contact-submit.php` saves to `leads`; contact form on `/contact.php`. |
| **Database** | ✅ | SQLite, tables: admin_users, clients, projects, quotes, quote_lines, invoices, invoice_lines, leads, settings. |
| **UI** | ✅ | Sidebar (Dashboard, CRM group, System group), content cards, empty states, form cards, responsive. |

**Conclusion:** The admin is complete and functional for the current scope. All critical flows (login → dashboard → CRM → quotes/invoices → settings) are wired and protected.

---

## ✅ Implemented after verification

| Feature | Status | Notes |
|--------|--------|-------|
| **SMTP / Email** | ✅ | Settings → Email tab: host, port, user, password, encryption, from, notify on new lead. Test email. Send quote/invoice by email from view pages. Uses PHP `mail()` if SMTP host empty. |
| **Admin users & roles** | ✅ | **Users** section (admin only): list, add/edit (username, email, role, password), delete. **Roles:** admin, editor, viewer. Permission helpers and enforcement on all admin pages. Change own password. |
| **Services catalog** | ✅ | **Services** in sidebar: list, add/edit (name, description, default price, sort order), delete. Quote and invoice forms: “Add from catalog” to insert a line from a service. |
| **Activity log** | ✅ | **Activity** in sidebar: list last 500 entries, filter by entity (client, project, quote, invoice, lead). Logs create/update/delete and key actions (sent email, marked paid, lead converted, status updated). |
| **Settings tabs** | ✅ | Settings page: **Company**, **Quotes & invoices**, **Email** tabs. Active tab preserved after save (redirect with `?tab=...`). |
| **CSRF protection** | ✅ | All admin POST forms use `csrf_token()` and `csrf_validate()`. Token regenerated after use. |

---

## Propositions — what the company can manage from the admin

Ideas below are ordered by impact and fit for a **digital agency** (web, SEO, performance marketing, branding). Implement in phases.

---

### 1. **SMTP / Email (high priority)** — ✅ Implemented

- **Why:** Send quote/invoice by email, lead notifications, password reset, contact-form auto-reply.
- **In admin:** Settings → **Email** tab: host, port, encryption (TLS/SSL), username, password, from name/address, notify on new lead. “Test email” form. Quote/Invoice view: “Send by email” button.
- **Usage:** Optional notify email on new lead; send quote/invoice by email from view page.
- **Tech:** `config/email.php`: PHP `mail()` if SMTP host empty, else socket SMTP (no Composer). Config in `settings` table.

---

### 2. **Admin users — sub-accounts / same-level accounts (high priority)** — ✅ Implemented

- **Why:** Multiple team members (same level) or limited sub-accounts (e.g. junior, freelancer) with different permissions.
- **In admin:** **Users** section (sidebar, admin only): list, Add/Edit (username, email, role, password), delete. Change own password (password-change.php).
- **Roles:** **Admin** — full access including Settings and Users. **Editor** — create/edit/delete clients, projects, quotes, invoices, leads; no Settings, no Users. **Viewer** — read-only on CRM and Activity.
- **DB:** `admin_users.role`. Helpers: `admin_require_role()`, `admin_can_manage_users()`, `admin_can_edit_settings()`, `admin_can_edit()`.
- **Optional (not done):** Invite by email, temporary password.

---

### 3. **Services / service catalog (medium priority)** — ✅ Implemented

- **Why:** Standardize what you sell (Web design, SEO, Performance marketing, etc.) and reuse in quotes/invoices.
- **In admin:** **Services** in sidebar — list, Add/Edit (name, description, default price, sort order), delete. Quote and invoice forms: “Add from catalog” dropdown + Add button to insert one line (description + default price, qty 1).
- **DB:** Table `services` (id, name, description, default_price, sort_order). No `service_id` on quote_lines/invoice_lines (lines copy name/price).

---

### 4. **Contracts / SOWs (medium priority)**

- **Why:** Attach a simple contract or scope of work to a project or quote.
- **In admin:** On Project or Quote: upload PDF or paste “Terms / SOW” text; store path or text in DB. Optional “Contract sent” date and status.
- **DB:** `projects.contract_url` or `contract_text`, `contract_sent_at`; or new table `contracts` linked to project/quote.

---

### 5. **Time tracking / timesheets (medium priority)**

- **Why:** Log hours per project or client for billing or internal reporting.
- **In admin:** **Time entries** — project, date, hours, description, optional billable flag. Report: hours by project/client/month. Optional “Add to invoice” from non-invoiced time.
- **DB:** Table `time_entries` (id, project_id, user_id, date, hours, description, billable, invoice_id nullable).

---

### 6. **Expenses (medium priority)**

- **Why:** Track project-related expenses (ads, tools, subcontractors) and optionally pass to client or invoice.
- **In admin:** **Expenses** — project, date, amount, category, description, receipt (file upload or link). List/filter by project; optional “Include in invoice” or “Reimbursable”.
- **DB:** Table `expenses` (id, project_id, date, amount, currency, category, description, receipt_path, invoice_id nullable).

---

### 7. **Notifications & activity log (medium priority)** — ✅ Activity log implemented

- **Why:** See what changed and when; optional in-app or email alerts.
- **In admin:** **Activity** in sidebar — list last 500 entries, filter by entity type (client, project, quote, invoice, lead). Columns: when, action, entity, details, user. Logs: create/update/delete (clients, projects, quotes, invoices, leads), sent_email (quote/invoice), marked_paid, status_updated (lead), converted_to_client (lead). New lead from contact form logged with no user.
- **DB:** Table `activity_log` (id, action, entity_type, entity_id, details, admin_user_id, created_at). Helper `log_activity($action, $entityType, $entityId, $details)` in `config/activity.php`.
- **Optional (not done):** In-app or email notifications (e.g. “New lead”, “Invoice overdue”).

---

### 8. **Client portal (lower priority)**

- **Why:** Let clients see their quotes/invoices and optionally pay online.
- **In admin:** Per client: “Portal access” on/off, optional login link or magic link. Public (signed) URLs for quote/invoice view (e.g. `/q/TOKEN`, `/i/TOKEN`) with optional “Accept quote” or “Pay invoice” (redirect to Stripe/PayPal).
- **DB:** Tokens in `quotes`/`invoices` or table `access_tokens`; optional `client_portal_enabled` on clients.

---

### 9. **Website / content (lower priority)**

- **Why:** Edit key site content without touching code.
- **In admin:** **Pages / Content** — list of “blocks” or pages (e.g. Home hero, Services intro, Contact CTA). Simple WYSIWYG or markdown; store in DB or JSON. Front-end loads content from API or PHP.
- **Scope:** Start with one or two pages (e.g. homepage tagline, contact text) to avoid a full CMS.

---

### 10. **Reports & dashboard widgets (lower priority)**

- **Why:** Revenue, pipeline, and workload at a glance.
- **In admin:** Dashboard: “Revenue this month”, “Quotes pending”, “Overdue invoices”, “New leads (7 days)”. Optional **Reports** page: export quotes/invoices by date range, by client, CSV/PDF.
- **Tech:** Queries on existing tables; cache if needed.

---

### Suggested implementation order

1. ~~**SMTP + email**~~ — ✅ Done.  
2. ~~**Admin users + roles**~~ — ✅ Done.  
3. ~~**Services catalog**~~ — ✅ Done.  
4. ~~**Activity log**~~ — ✅ Done.  
5. **Next:** contracts (SOW), time tracking, expenses, in-app/email notifications, client portal, content, reports as needed.

---

*Document generated for Systemiks admin. Adjust priorities and scope to match your roadmap.*
