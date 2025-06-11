
[📘 GIẢI THÍCH PROJECT - HỆ THỐNG TÀI KHOẢN NGÂN HÀNG SỐ]

1. Namespace: Tất cả class thuộc namespace XYZBank\Accounts (tuân chuẩn PSR-4)
2. Abstract Class: BankAccount chứa logic nền tảng cho các loại tài khoản
3. Interface: InterestBearing dành cho các tài khoản sinh lãi
4. Trait: TransactionLogger để log giao dịch (echo console)
5. Lớp con:
   - SavingsAccount: Có tính lãi, không cho rút nếu < 1 triệu, dùng trait
   - CheckingAccount: Không giới hạn số dư, không tính lãi, dùng trait
6. Static class: Bank chứa tổng số tài khoản và tên ngân hàng
7. Iterable class: AccountCollection cho phép quản lý danh sách tài khoản, lọc số dư ≥ 10 triệu
8. test.php: Kiểm thử đúng theo yêu cầu đề bài

🔥 Mọi logic đều được mô hình hóa hướng đối tượng đúng chuẩn nâng cao PHP.
🔥 Không dùng database, tất cả hoạt động trong RAM.

-> Ready to plug into real backend module hoặc trình diễn mô phỏng.
