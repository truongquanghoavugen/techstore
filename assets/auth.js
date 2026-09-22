/* GearZone Authentication - frontend validation/demo layer */
(() => {
  "use strict";

  const USERS_KEY = "gearzone_demo_users_v1";
  const SESSION_KEY = "gearzone_demo_session_v1";

  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => [
    ...root.querySelectorAll(selector),
  ];

  const getUsers = () => {
    try {
      return JSON.parse(localStorage.getItem(USERS_KEY) || "[]");
    } catch {
      return [];
    }
  };

  const saveUsers = (users) =>
    localStorage.setItem(USERS_KEY, JSON.stringify(users));

  async function sha256(value) {
    if (window.crypto?.subtle) {
      const data = new TextEncoder().encode(value);
      const digest = await crypto.subtle.digest("SHA-256", data);
      return [...new Uint8Array(digest)]
        .map((b) => b.toString(16).padStart(2, "0"))
        .join("");
    }
    /* Fallback only for old browsers; replace with backend hashing in production. */
    return btoa(unescape(encodeURIComponent(value)));
  }

  const normalize = (value) => value.trim().toLowerCase();

  function showAlert(id, type, message) {
    const el = document.getElementById(id);
    if (!el) return;
    el.className = `auth-alert show ${type}`;
    el.textContent = message;
  }

  function clearErrors(form) {
    $$(".auth-error", form).forEach((el) => (el.textContent = ""));
    $$(".auth-input, .auth-select", form).forEach((el) =>
      el.classList.remove("is-invalid"),
    );
  }

  function setError(form, field, message) {
    const input = form.querySelector(`[name="${field}"]`);
    const error = form.querySelector(`[data-error-for="${field}"]`);
    if (input) input.classList.add("is-invalid");
    if (error) error.textContent = message;
  }

  function togglePasswords() {
    $$("[data-toggle-password]").forEach((button) => {
      button.addEventListener("click", () => {
        const input = document.getElementById(button.dataset.togglePassword);
        if (!input) return;
        const visible = input.type === "text";
        input.type = visible ? "password" : "text";
        const icon = $("i", button);
        if (icon)
          icon.className = visible
            ? "fa-regular fa-eye"
            : "fa-regular fa-eye-slash";
        button.setAttribute(
          "aria-label",
          visible ? "Hiển thị mật khẩu" : "Ẩn mật khẩu",
        );
      });
    });
  }

  function passwordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
    if (/\d/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    return score;
  }

  function setupPasswordMeter() {
    const input = $("#registerPassword");
    const bar = $("#passwordBar");
    const text = $("#passwordText");
    if (!input || !bar || !text) return;

    input.addEventListener("input", () => {
      const score = passwordStrength(input.value);
      const widths = ["0%", "25%", "50%", "75%", "100%"];
      const labels = ["Chưa nhập mật khẩu", "Yếu", "Trung bình", "Khá", "Mạnh"];
      bar.style.width = widths[score];
      text.textContent = labels[score];
    });
  }

  function validateCommonPassword(password) {
    if (!password) return "Vui lòng nhập mật khẩu.";
    if (password.length < 8) return "Mật khẩu phải có ít nhất 8 ký tự.";
    return "";
  }

  async function setupRegister() {
    const form = $("#registerForm");
    if (!form) return;

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      clearErrors(form);
      const fullName = $("#fullName").value.trim();
      const email = normalize($("#registerEmail").value);
      const phone = $("#phone").value.trim();
      const username = normalize($("#username").value);
      const password = $("#registerPassword").value;
      const confirm = $("#confirmPassword").value;
      const terms = $("#terms").checked;

      let valid = true;

      if (!fullName || fullName.length < 2) {
        setError(form, "fullName", "Vui lòng nhập họ và tên hợp lệ.");
        valid = false;
      }
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        setError(form, "email", "Email không đúng định dạng.");
        valid = false;
      }
      if (phone && !/^(0|\+84)\d{9,10}$/.test(phone.replace(/[\s.-]/g, ""))) {
        setError(form, "phone", "Số điện thoại không hợp lệ.");
        valid = false;
      }
      if (!/^[a-z0-9._-]{3,30}$/.test(username)) {
        setError(
          form,
          "username",
          "Tên đăng nhập phải từ 3–30 ký tự và chỉ dùng a-z, 0-9, ., _, -.",
        );
        valid = false;
      }
      const passwordError = validateCommonPassword(password);
      if (passwordError) {
        setError(form, "password", passwordError);
        valid = false;
      }
      if (password !== confirm) {
        setError(
          form,
          "confirmPassword",
          "Mật khẩu xác nhận không trùng khớp.",
        );
        valid = false;
      }
      if (!terms) {
        setError(form, "terms", "Bạn cần đồng ý với điều khoản để đăng ký.");
        valid = false;
      }

      const users = getUsers();
      if (users.some((u) => normalize(u.email) === email)) {
        setError(form, "email", "Email này đã được đăng ký.");
        valid = false;
      }
      if (users.some((u) => normalize(u.username) === username)) {
        setError(form, "username", "Tên đăng nhập này đã tồn tại.");
        valid = false;
      }

      if (!valid) {
        showAlert(
          "registerAlert",
          "error",
          "Vui lòng kiểm tra lại thông tin đăng ký.",
        );
        return;
      }

      const passwordHash = await sha256(password);
      users.push({
        id: `demo_${Date.now()}`,
        fullName,
        email,
        phone,
        username,
        passwordHash,
        verified: false,
        locked: false,
        createdAt: new Date().toISOString(),
      });
      saveUsers(users);

      form.hidden = true;
      $("#registerSuccess").classList.add("show");
      $("#registerAlert").className = "auth-alert";
    });
  }

  async function setupLogin() {
    const form = $("#loginForm");
    if (!form) return;

    const saved = localStorage.getItem("gearzone_remember_account");
    if (saved) $("#loginAccount").value = saved;

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      clearErrors(form);

      const account = normalize($("#loginAccount").value);
      const password = $("#loginPassword").value;
      let valid = true;

      if (!account) {
        setError(form, "account", "Vui lòng nhập email hoặc tên đăng nhập.");
        valid = false;
      }
      const passwordError = validateCommonPassword(password);
      if (passwordError) {
        setError(form, "password", passwordError);
        valid = false;
      }
      if (!valid) {
        showAlert("loginAlert", "error", "Thông tin đăng nhập chưa hợp lệ.");
        return;
      }

      const users = getUsers();
      const user = users.find(
        (u) =>
          normalize(u.email) === account || normalize(u.username) === account,
      );

      if (!user) {
        showAlert("loginAlert", "error", "Sai tài khoản hoặc mật khẩu.");
        return;
      }
      if (user.locked) {
        showAlert(
          "loginAlert",
          "error",
          "Tài khoản đang bị khóa. Vui lòng liên hệ quản trị viên.",
        );
        return;
      }
      if (user.verified === false) {
        showAlert(
          "loginAlert",
          "warning",
          "Tài khoản chưa xác thực. Hãy xác thực tài khoản trước khi đăng nhập.",
        );
        return;
      }

      const hash = await sha256(password);
      if (hash !== user.passwordHash) {
        showAlert("loginAlert", "error", "Sai tài khoản hoặc mật khẩu.");
        return;
      }

      const remember = $("#remember").checked;
      if (remember) localStorage.setItem("gearzone_remember_account", account);
      else localStorage.removeItem("gearzone_remember_account");

      sessionStorage.setItem(
        SESSION_KEY,
        JSON.stringify({
          id: user.id,
          username: user.username,
          fullName: user.fullName,
          loggedInAt: new Date().toISOString(),
        }),
      );

      showAlert(
        "loginAlert",
        "success",
        `Đăng nhập thành công. Xin chào ${user.fullName}!`,
      );

      /*
       * Backend integration point:
       * Replace the demo sessionStorage flow with POST /api/login
       * and redirect based on the backend response/token.
       */
    });
  }

  function setupForgotPassword() {
    const requestForm = $("#forgotRequestForm");
    const resetForm = $("#resetPasswordForm");
    if (!requestForm || !resetForm) return;

    $$(".auth-method").forEach((button) => {
      button.addEventListener("click", () => {
        $$(".auth-method").forEach((b) => b.classList.remove("active"));
        button.classList.add("active");
        $("#resetMethod").value = button.dataset.method;
      });
    });

    requestForm.addEventListener("submit", (event) => {
      event.preventDefault();
      clearErrors(requestForm);
      const account = normalize($("#forgotAccount").value);
      const users = getUsers();
      const user = users.find(
        (u) =>
          normalize(u.email) === account || normalize(u.username) === account,
      );

      if (!account) {
        setError(
          requestForm,
          "forgotAccount",
          "Vui lòng nhập email hoặc tên đăng nhập.",
        );
        showAlert("forgotAlert", "error", "Vui lòng nhập thông tin tài khoản.");
        return;
      }

      /*
       * Security note:
       * Real backend should avoid revealing whether an account exists.
       * This demo shows a generic success message instead.
       */
      if (!user) {
        showAlert(
          "forgotAlert",
          "success",
          "Nếu tài khoản tồn tại, hướng dẫn khôi phục đã được gửi theo phương thức bạn chọn.",
        );
      } else {
        const method = $("#resetMethod").value;
        const methodLabel =
          method === "email"
            ? "email"
            : method === "otp"
              ? "OTP"
              : "link đặt lại mật khẩu";
        showAlert(
          "forgotAlert",
          "success",
          `Demo: yêu cầu xác thực qua ${methodLabel} đã được tạo.`,
        );
      }

      $("#forgotStepRequest").hidden = true;
      $("#forgotStepReset").hidden = false;
    });

    resetForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      clearErrors(resetForm);

      const code = $("#verificationCode").value.trim();
      const newPassword = $("#newPassword").value;
      const confirm = $("#confirmNewPassword").value;
      let valid = true;

      if (!code) {
        setError(resetForm, "code", "Vui lòng nhập mã xác thực/OTP.");
        valid = false;
      }
      const passwordError = validateCommonPassword(newPassword);
      if (passwordError) {
        setError(resetForm, "newPassword", passwordError);
        valid = false;
      }
      if (newPassword !== confirm) {
        setError(
          resetForm,
          "confirmNewPassword",
          "Mật khẩu xác nhận không trùng khớp.",
        );
        valid = false;
      }
      if (!valid) {
        showAlert("forgotAlert", "error", "Vui lòng kiểm tra lại thông tin.");
        return;
      }

      const account = normalize($("#forgotAccount").value);
      const users = getUsers();
      const userIndex = users.findIndex(
        (u) =>
          normalize(u.email) === account || normalize(u.username) === account,
      );

      if (userIndex >= 0) {
        users[userIndex].passwordHash = await sha256(newPassword);
        users[userIndex].verified = true;
        saveUsers(users);
      }

      $("#forgotStepReset").hidden = true;
      $("#forgotSuccess").classList.add("show");
      $("#forgotAlert").className = "auth-alert";
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    togglePasswords();
    setupPasswordMeter();
    setupRegister();
    setupLogin();
    setupForgotPassword();
  });
})();
