# ShopStream

Shopstream is an online shopping platform that incorporates CRUD and RBAC. There are four roles:
- Customers can view all products, place orders, and create support tickets. They can manage their own orders and tickets. They can mark tickets as open or closed.
- Sellers can create and manage their own products, as well as handle the status of orders from customers for said products.
- Support can view all tickets and their corresponding customers and products. They can mark the status of these tickets as waiting for response, in progress, or resolved.
- Admins have all permissions, including the management of users, products, orders, and tickets.

## Tabs
- Products
- Orders
- Support
- Users

## Roles
- Customer
- Seller
- Support
- Admin

## Permissions
- create-users, read-users, update-users, delete-users
- create-products, read-products, update-products, delete-products
- create-orders, read-orders, update-orders, delete-orders
- create-tickets, read-tickets, update-tickets, delete-tickets
