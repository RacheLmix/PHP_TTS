// Toast nâng cao: loại thông báo ERROR, SUCCESS, WARNING
function showToast(message = "Thao tác thành công!", type = "success") {
  const toast = document.createElement("div");
  toast.className = "toast";
  toast.innerText = message;

  // Thêm màu sắc tùy theo type
  if (type === "error") {
    toast.style.backgroundColor = "rgba(244, 67, 54, 0.9)"; // đỏ
  } else if (type === "warning") {
    toast.style.backgroundColor = "rgba(255, 193, 7, 0.9)"; // vàng
    toast.style.color = "#222";
  } else {
    toast.style.backgroundColor = "rgba(0, 200, 83, 0.9)"; // xanh lá
  }

  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}

// Dark mode toggle + nhớ trạng thái qua localStorage
const toggle = document.querySelector("#toggle-dark");
if (toggle) {
  // Khôi phục trạng thái dark mode lúc trước
  if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark");
    toggle.checked = true;
  }

  toggle.addEventListener("change", () => {
    document.body.classList.toggle("dark");
    if (document.body.classList.contains("dark")) {
      localStorage.setItem("darkMode", "enabled");
      showToast("Dark mode đã bật 🌙", "success");
    } else {
      localStorage.setItem("darkMode", "disabled");
      showToast("Light mode đã bật ☀️", "success");
    }
  });
}

// Spinner hiện khi submit form, ẩn sau 2 giây
document.querySelectorAll("form").forEach(form => {
  form.addEventListener("submit", e => {
    showSpinner();
    setTimeout(hideSpinner, 2000);
  });
});

// Show spinner
function showSpinner() {
  const spinner = document.querySelector(".spinner");
  if (spinner) spinner.style.display = "block";
}

// Hide spinner
function hideSpinner() {
  const spinner = document.querySelector(".spinner");
  if (spinner) spinner.style.display = "none";
}

// Hover effect: thêm xóa class khi hover button (nếu muốn phức tạp hơn)
// Không cần code riêng do CSS đã lo

// Thêm: Animation nhấn phím (nếu muốn)
// window.addEventListener("keydown", e => {
//   console.log(`Key pressed: ${e.key}`);
// });
