# Académie — Student Management System

A school student management system built with Laravel, featuring Admin/Teacher role-based access and a custom "Académie" design system (server-rendered Blade views styled with Tailwind CSS).

## 🛠 Tech Stack

- **Backend:** PHP, Laravel
- **Database:** MySQL
- **Frontend:** Blade templates, Tailwind CSS (via `@tailwindcss/vite`), Alpine.js, Chart.js
- **Build tool:** Vite + `laravel-vite-plugin`
- **Code quality:** Laravel Pint, PHPUnit

## ✨ Features

### Authentication & Authorization

- Login, password reset, email verification, profile update/delete (Laravel Breeze-based)
- No public self-registration — accounts are created by the Admin only
- Two roles: `admin` and `teacher` (stored on `users.role`)
- `EnsureRole` middleware (aliased `role`) gates routes via `role:admin` / `role:teacher`
- UI (sidebar, theme colors, dashboard) adapts per role — separate `.theme-admin` / `.theme-teacher` styling

### Management (Admin)

- **Classrooms** — card-grid view (class name, grade badge, student count, room, homeroom teacher), create/edit via modal, delete, pagination
- **Students** — CRUD via modal, optional filter by classroom, pagination
- **Subjects** — CRUD via modal, pagination
- **User accounts** — dedicated pages (list/create/edit); creating a user always assigns the `teacher` role server-side and generates a random one-time password shown once after creation

### Grades

- Each grade splits into 4 score components: **oral** / **15-minute quiz** / **45-minute test** / **final exam**, weighted 1 / 1 / 2 / 3 respectively for the weighted average
- **Grade entry** page (`/grades/entry`) — teachers pick classroom, subject, and semester via chip selectors, then enter all 4 score columns per student in one table, with a live-computed average column
- **Grade list** page (`/grades`) — CRUD via modal, paginated

### Class Schedule (Lessons)

- **Admin** (`/admin/schedule`) — schedules lessons for the whole school: pick a classroom via chip selector, view a Monday–Saturday × Period 1–5 grid, click an empty cell to assign a subject + teacher, edit/delete existing lessons; conflict checks prevent double-booking the same classroom or the same teacher at the same time slot
- **Teacher** (`/schedule`) — read-only view of their own weekly teaching schedule (lessons are arranged by the Admin)

### Student Comments (Teacher)

- Teachers pick a classroom (chip selector) then a student, write a conduct rating (Tốt/Khá/Trung bình) and free-text comment, and view the full comment history for that student

### Dashboard

- **Admin:** total students/classrooms/subjects/school-wide average, a Chart.js bar chart of average score per classroom, an academic-performance breakdown per classroom (Xuất sắc/Giỏi/Khá/Yếu) for the latest semester, and a top-5 highest-average-score student leaderboard
- **Teacher:** subjects currently taught, number of classes in charge, today's lesson count, today's lesson timeline, and the list of classes they teach — all derived from their own `Lesson` records

### Multi-language

- Vietnamese, English, and Japanese (`vi` / `en` / `ja`)
- Vietnamese strings are the literal translation keys in the code; `lang/en.json` and `lang/ja.json` provide the English/Japanese overrides
- Language switch via `/lang/{locale}`, persisted in session, applied on every request by the `SetLocale` middleware

## 🧭 Route Summary

| Method | Route | Description | Access |
|--------|-------|--------------|--------|
| GET | `/` | Redirects to the dashboard | Both |
| GET | `/dashboard` | Dashboard (admin stats / teacher overview) | Both |
| GET, POST | `/login`, `/logout`, `/forgot-password`, `/reset-password` | Authentication (Breeze) | Both |
| GET, PATCH, DELETE | `/profile` | View/update/delete own profile | Both |
| GET, POST, PUT, DELETE | `/grades`, `/grades/{grade}` | Grade list CRUD | Both |
| GET, POST | `/grades/entry` | Class-wide grade entry sheet | Both |
| GET | `/schedule` | View own weekly teaching schedule | Teacher |
| GET, POST | `/comments` | View/write student comments | Teacher |
| GET, POST, PUT, DELETE | `/classrooms`, `/classrooms/{classroom}` | Classroom CRUD | Admin |
| GET, POST, PUT, DELETE | `/students`, `/students/{student}` | Student CRUD | Admin |
| GET, POST, PUT, DELETE | `/subjects`, `/subjects/{subject}` | Subject CRUD | Admin |
| GET, POST, PUT, DELETE | `/users`, `/users/{user}` | User account management | Admin |
| GET, POST, PUT, DELETE | `/admin/schedule`, `/admin/schedule/{lesson}` | School-wide lesson scheduling | Admin |
| GET | `/lang/{locale}` | Switch UI language (vi/en/ja) | Both |

> `/grades` and `/grades/entry` carry no `role:` middleware — both Admin and Teacher can list/edit grades and use the class-wide entry sheet.

## 📂 Project Structure

```
student-management/
├── app/
│   ├── Models/                       # Classroom, Student, Subject, Grade, Lesson, Comment, User
│   └── Http/
│       ├── Controllers/              # Dashboard, Classroom, Student, Subject, Grade,
│       │                             # Lesson (teacher view), AdminLesson (admin scheduling),
│       │                             # Comment, User, Profile, Auth/*
│       └── Middleware/               # EnsureRole (role:admin|teacher), SetLocale
├── database/
│   ├── migrations/                   # users, classrooms, students, subjects, grades,
│   │                                 # lessons, comments + incremental schema changes
│   ├── seeders/DatabaseSeeder.php    # Demo accounts + sample classrooms/students/grades/lessons
│   └── factories/UserFactory.php     # User factory (admin/teacher states)
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php     # Main layout: sidebar, topbar, admin/teacher theming
│   │   ├── classrooms/index.blade.php
│   │   ├── students/index.blade.php
│   │   ├── subjects/index.blade.php
│   │   ├── grades/{index,entry}.blade.php
│   │   ├── teaching/{schedule,comments}.blade.php  # Teacher-facing pages
│   │   ├── admin/schedule.blade.php  # Admin school-wide lesson scheduling
│   │   ├── users/{index,create,edit}.blade.php
│   │   ├── dashboard.blade.php       # Admin stats / Teacher overview
│   │   └── components/               # x-page-head, x-avatar, x-pagination, x-lang-switcher...
│   └── css/app.css                   # Design system (Tailwind v4 + custom .panel/.tbl/.btn/.sched...)
├── lang/{en,ja}.json                 # English/Japanese translation overrides
├── routes/web.php                    # All application routes
├── composer.json                     # PHP dependencies + composer scripts (setup, dev, test)
└── package.json                      # Frontend dependencies + Vite scripts (build, dev)
```

## ⚙️ Installation

```bash
git clone https://github.com/KaterukuAnh/student-management.git
cd student-management

# PHP dependencies
composer install

# Environment
cp .env.example .env
php artisan key:generate
# Edit .env: set DB_CONNECTION=mysql and DB_DATABASE=student_management
# (create the `student_management` database in MySQL beforehand)

# JS dependencies
npm install

# Database
php artisan migrate
php artisan db:seed

# Build frontend assets
npm run build
```

Run the app:

```bash
php artisan serve
```

Or, for active development (app server + queue listener + log tailing + Vite dev server together):

```bash
composer run dev
```

Open your browser at `http://localhost:8000`.

## 👤 Demo Accounts

Created by `php artisan db:seed`, all using the password **`password`**:

| Role    | Email                      | Name           |
| ------- | -------------------------- | -------------- |
| Admin   | `admin@academie.edu.vn`    | Quản trị viên  |
| Teacher | `teacher@academie.edu.vn`  | Lê Thu Hà      |
| Teacher | `lan.toan@academie.edu.vn` | Nguyễn Thị Lan |
| Teacher | `binh.van@academie.edu.vn` | Trần Văn Bình  |
| Teacher | `hong.anh@academie.edu.vn` | Phạm Thị Hồng  |

The seeder also creates 3 classrooms, 5 subjects, 12 students, two semesters of grades, a sample weekly lesson schedule, and a few student comments.

## 🩹 Troubleshooting

- **MySQL connection error / "Access denied for user 'root'"** — Make sure MySQL is started in the XAMPP Control Panel. If it won't start, port 3306 may already be taken by another MySQL installation (e.g. a separately installed MySQL Windows service) — stop that service or update `DB_PORT` in `.env` to match the MySQL instance you're actually running.
- **`composer install` fails because the `zip` extension is missing** — Open the `php.ini` used by your CLI PHP (run `php --ini` to find it), uncomment/add `extension=zip`, then restart your terminal.
- **Page loads with no styling (missing CSS) after `php artisan serve`** — This happens when neither the Vite dev server nor a production build is running. Run `npm run build` (or `composer run dev` for active development) to generate the assets in `public/build`.

## 👤 Author

DANG NGUYEN MINH ANH (KaterukuAnh)

---

# Académie — Hệ Thống Quản Lý Học Sinh

Hệ thống quản lý học sinh cho trường học, xây dựng bằng Laravel, có phân quyền Admin/Giáo viên, theo phong cách thiết kế riêng "Académie" (giao diện Blade render phía server, style bằng Tailwind CSS).

## 🛠 Công nghệ sử dụng

- **Backend:** PHP, Laravel
- **Cơ sở dữ liệu:** MySQL
- **Frontend:** Blade templates, Tailwind CSS (qua `@tailwindcss/vite`), Alpine.js, Chart.js
- **Build tool:** Vite + `laravel-vite-plugin`
- **Chất lượng code:** Laravel Pint, PHPUnit

## ✨ Tính năng

### Authentication & Authorization

- Đăng nhập, quên/đặt lại mật khẩu, xác minh email, sửa/xóa hồ sơ cá nhân (dựa trên Laravel Breeze)
- Không có đăng ký công khai — tài khoản chỉ được tạo bởi Admin
- 2 vai trò: `admin` và `teacher` (lưu ở cột `users.role`)
- Middleware `EnsureRole` (alias `role`) chặn route theo `role:admin` / `role:teacher`
- Giao diện (sidebar, màu theme, dashboard) thay đổi theo vai trò — style riêng `.theme-admin` / `.theme-teacher`

### Quản lý (Admin)

- **Lớp học** — hiển thị dạng card lưới (tên lớp, badge khối, sĩ số, phòng học, GV chủ nhiệm), tạo/sửa qua modal, xóa, có phân trang
- **Học sinh** — CRUD qua modal, có thể lọc theo lớp, có phân trang
- **Môn học** — CRUD qua modal, có phân trang
- **Quản lý tài khoản** — trang riêng (danh sách/tạo/sửa); tạo tài khoản luôn gán role `teacher` ở server và tự sinh mật khẩu ngẫu nhiên, hiển thị một lần duy nhất sau khi tạo

### Điểm số

- Mỗi điểm gồm 4 cột thành phần: **miệng** / **15 phút** / **45 phút** / **cuối kỳ**, tính điểm trung bình theo trọng số 1 / 1 / 2 / 3
- Trang **Nhập điểm** (`/grades/entry`) — giáo viên chọn lớp, môn, học kỳ qua chip, sau đó nhập đủ 4 cột điểm cho từng học sinh trong 1 bảng, có cột điểm trung bình tính tự động
- Trang **Danh sách điểm** (`/grades`) — CRUD qua modal, có phân trang

### Thời khóa biểu

- **Admin** (`/admin/schedule`) — xếp lịch dạy cho toàn trường: chọn lớp qua chip, xem lưới Thứ 2–Thứ 7 × Tiết 1–5, bấm vào ô trống để xếp môn học + giáo viên, sửa/xóa tiết đã xếp; có kiểm tra chống trùng (trùng lớp hoặc trùng giáo viên ở cùng khung giờ)
- **Teacher** (`/schedule`) — chỉ xem lịch dạy của chính mình (do Admin xếp sẵn), không có quyền tạo/sửa/xóa

### Nhận xét học sinh (Teacher)

- Giáo viên chọn lớp (chip) rồi chọn học sinh, ghi nhận hạnh kiểm (Tốt/Khá/Trung bình) kèm nội dung nhận xét, xem lại toàn bộ lịch sử nhận xét của học sinh đó

### Dashboard

- **Admin:** tổng số học sinh/lớp/môn học/điểm trung bình toàn trường, biểu đồ cột (Chart.js) điểm trung bình theo lớp, bảng xếp loại học lực theo lớp (Xuất sắc/Giỏi/Khá/Yếu) ở học kỳ gần nhất, top 5 học sinh có điểm trung bình cao nhất
- **Teacher:** bộ môn đang dạy, số lớp đang phụ trách, số tiết dạy hôm nay, lịch dạy hôm nay theo từng tiết, danh sách lớp đang dạy — tất cả suy ra trực tiếp từ dữ liệu `Lesson` của giáo viên đó

### Đa ngôn ngữ

- Tiếng Việt, English, 日本語 (`vi` / `en` / `ja`)
- Chuỗi tiếng Việt là key dịch gốc ngay trong code; `lang/en.json` và `lang/ja.json` cung cấp bản dịch tiếng Anh/Nhật
- Chuyển ngôn ngữ qua `/lang/{locale}`, lưu vào session, áp dụng ở mỗi request bởi middleware `SetLocale`

## 🧭 Tổng hợp Route chính

| Method | Route | Mô tả | Quyền truy cập |
|--------|-------|--------------|--------|
| GET | `/` | Chuyển hướng tới trang dashboard | Cả hai |
| GET | `/dashboard` | Trang tổng quan (thống kê cho Admin / tổng quan cho Teacher) | Cả hai |
| GET, POST | `/login`, `/logout`, `/forgot-password`, `/reset-password` | Đăng nhập/đăng xuất, quên/đặt lại mật khẩu | Cả hai |
| GET, PATCH, DELETE | `/profile` | Xem/sửa/xóa hồ sơ cá nhân | Cả hai |
| GET, POST, PUT, DELETE | `/grades`, `/grades/{grade}` | CRUD danh sách điểm | Cả hai |
| GET, POST | `/grades/entry` | Bảng nhập điểm theo lớp | Cả hai |
| GET | `/schedule` | Xem thời khóa biểu của chính mình | Teacher |
| GET, POST | `/comments` | Xem/ghi nhận xét học sinh | Teacher |
| GET, POST, PUT, DELETE | `/classrooms`, `/classrooms/{classroom}` | CRUD lớp học | Admin |
| GET, POST, PUT, DELETE | `/students`, `/students/{student}` | CRUD học sinh | Admin |
| GET, POST, PUT, DELETE | `/subjects`, `/subjects/{subject}` | CRUD môn học | Admin |
| GET, POST, PUT, DELETE | `/users`, `/users/{user}` | Quản lý tài khoản | Admin |
| GET, POST, PUT, DELETE | `/admin/schedule`, `/admin/schedule/{lesson}` | Xếp thời khóa biểu toàn trường | Admin |
| GET | `/lang/{locale}` | Chuyển ngôn ngữ giao diện (vi/en/ja) | Cả hai |

> `/grades` và `/grades/entry` không gắn middleware `role:` nào — cả Admin và Teacher đều xem/sửa được danh sách điểm và dùng bảng nhập điểm theo lớp.

## 📂 Cấu trúc thư mục

```
student-management/
├── app/
│   ├── Models/                       # Classroom, Student, Subject, Grade, Lesson, Comment, User
│   └── Http/
│       ├── Controllers/              # Dashboard, Classroom, Student, Subject, Grade,
│       │                             # Lesson (xem lịch của Teacher), AdminLesson (Admin xếp lịch),
│       │                             # Comment, User, Profile, Auth/*
│       └── Middleware/               # EnsureRole (role:admin|teacher), SetLocale
├── database/
│   ├── migrations/                   # users, classrooms, students, subjects, grades,
│   │                                 # lessons, comments + các thay đổi schema tiếp theo
│   ├── seeders/DatabaseSeeder.php    # Tài khoản demo + dữ liệu mẫu (lớp/học sinh/điểm/lịch dạy)
│   └── factories/UserFactory.php     # Factory tạo User (state admin/teacher)
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php     # Layout chính: sidebar, topbar, theme admin/teacher
│   │   ├── classrooms/index.blade.php
│   │   ├── students/index.blade.php
│   │   ├── subjects/index.blade.php
│   │   ├── grades/{index,entry}.blade.php
│   │   ├── teaching/{schedule,comments}.blade.php  # Trang dành cho Teacher
│   │   ├── admin/schedule.blade.php  # Trang Admin xếp lịch toàn trường
│   │   ├── users/{index,create,edit}.blade.php
│   │   ├── dashboard.blade.php       # Thống kê Admin / Tổng quan Teacher
│   │   └── components/               # x-page-head, x-avatar, x-pagination, x-lang-switcher...
│   └── css/app.css                   # Design system (Tailwind v4 + class riêng .panel/.tbl/.btn/.sched...)
├── lang/{en,ja}.json                 # Bản dịch tiếng Anh/Nhật
├── routes/web.php                    # Toàn bộ route của ứng dụng
├── composer.json                     # Dependencies PHP + composer scripts (setup, dev, test)
└── package.json                      # Dependencies frontend + Vite scripts (build, dev)
```

## ⚙️ Cài đặt

```bash
git clone https://github.com/KaterukuAnh/student-management.git
cd student-management

# Cài dependencies PHP
composer install

# Thiết lập môi trường
cp .env.example .env
php artisan key:generate
# Sửa .env: đặt DB_CONNECTION=mysql và DB_DATABASE=student_management
# (tạo database `student_management` trong MySQL trước khi migrate)

# Cài dependencies JS
npm install

# Khởi tạo database
php artisan migrate
php artisan db:seed

# Build assets frontend
npm run build
```

Chạy ứng dụng:

```bash
php artisan serve
```

Hoặc, để phát triển (chạy đồng thời server + queue listener + log + Vite dev server):

```bash
composer run dev
```

Mở trình duyệt tại `http://localhost:8000`.

## 👤 Tài khoản demo

Được tạo bởi `php artisan db:seed`, tất cả dùng mật khẩu **`password`**:

| Vai trò | Email                      | Tên            |
| ------- | -------------------------- | -------------- |
| Admin   | `admin@academie.edu.vn`    | Quản trị viên  |
| Teacher | `teacher@academie.edu.vn`  | Lê Thu Hà      |
| Teacher | `lan.toan@academie.edu.vn` | Nguyễn Thị Lan |
| Teacher | `binh.van@academie.edu.vn` | Trần Văn Bình  |
| Teacher | `hong.anh@academie.edu.vn` | Phạm Thị Hồng  |

Seeder cũng tạo sẵn 3 lớp học, 5 môn học, 12 học sinh, điểm của 2 học kỳ, thời khóa biểu mẫu trong tuần, và một số nhận xét học sinh mẫu.

## 🩹 Khắc phục lỗi thường gặp

- **Lỗi kết nối MySQL / "Access denied for user 'root'"** — Kiểm tra MySQL trong XAMPP Control Panel đã bấm Start chưa. Nếu không start được, có thể port 3306 đang bị một MySQL khác chiếm dụng (ví dụ MySQL cài sẵn dạng Windows service) — tắt service đó hoặc đổi `DB_PORT` trong `.env` cho khớp với MySQL đang chạy thật.
- **`composer install` báo lỗi thiếu extension `zip`** — Mở file `php.ini` mà CLI PHP đang dùng (chạy `php --ini` để biết file nào), bật/thêm dòng `extension=zip`, rồi khởi động lại terminal.
- **Trang chạy được nhưng mất hết CSS/style sau khi `php artisan serve`** — Do chưa chạy Vite dev server hoặc chưa build assets production. Chạy `npm run build` (hoặc `composer run dev` khi đang phát triển) để tạo thư mục `public/build`.

## 👤 Tác giả

DANG NGUYEN MINH ANH (KaterukuAnh)
