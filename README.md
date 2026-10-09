# URL Shortener (Laravel 12)
Multi-company URL shortener with role-based access and invitation-based onboarding

## Requirements
- PHP 8.2+, Composer, MySQL, Node.js & NPM

## Roles
- SuperAdmin — platform-level, created via seeder
- Admin — belongs to a company
- Member — belongs to a company

## Rules
Invitations:
- SuperAdmin can invite an Admin in a new company
- Admin can invite another Admin or Member in their own company

URL Shortener:
- Admin and Member can create short URLs and SuperAdmin cannot
- SuperAdmin sees all short URLs across all companies
- Admin sees only their company URLs
- Member sees only their own URLs
- All short URLs are publicly resolvable and redirect to the original URL

## Setup
git clone https://github.com/VishalKumarPrajapat/urls-shortener.git
cd urls-shortener
CMD->  composer install
copy .env.example .env
CMD-> php artisan key:generate


Create MySQL database `urls_shortener` and set in `.env`:
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=urls_shortener
DB_USERNAME=root
DB_PASSWORD=
 

CMD->  php artisan migrate --seed
CMD->  npm install
CMD->  npm run build
CMD->  php artisan serve

 
Visit: http://127.0.0.1:8000 OR http://localhost:8000

SuperAdmin credentials:-
- Email: `superadmin@gmail.com`
- Password: `pwd@123`

## How to Use
1. Login as SuperAdmin -> Invite New Client (Client Name + Email) -> Send
2. Copy invitation URL from list -> open in new tab -> create account
3. New user becomes Admin of the newly created company
4. Admin can then Invite New Team Member (Admin or Member)
5. Admin and Member can create short URLs
6. Short URLs are publicly accessible and redirect to the original URL

## Tests
CMD-> php artisan test
 
Covers:
- Admin and Member can create short URLs
- SuperAdmin cannot create short URLs
- Admin sees only their company short URLs
- Member sees only their own short URLs
- Short URLs are publicly resolvable and redirect
 