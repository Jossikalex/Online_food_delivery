🍴 Ella Kitchen Café – Admin Panel
The Admin Panel is a compact, single-tenant back-office suite written in vanilla PHP-MySQL.
It provides managers with tools to manage menu items, orders, customers, staff, and reports.

📂 File Overview
connection.php  
Opens one UTF-8 MySQL link that every page re-uses.

login.php  
Hashes staff passwords with password_verify() and drops the user into Dashboard.php.
A session cookie keeps the identity alive across pages.

sidebar.php  
Navigation sidebar that links to the five core workspaces.

🖥️ Core Workspaces
Menu (menu.php)

Full CRUD for dishes/categories

In-page edit modal with live image preview

Toggle “active” instantly hides/shows items on the customer side

Orders (orders.php + saveOrderStatus.php)

Read-only order feed

Drop-down fires a POST to update status (pending ➜ delivered)

Customers (customers.php)

Searchable, paginated list

One-click block/unblock or soft-delete (cascades to customer’s history)

Staff Registration (Registration.php)

Creates waiter or delivery accounts

Server-side validation and email uniqueness check

Reports (report.php)

Period picker (today … last month)

Returns delivered-order counts, revenue, and top-5 selling items
