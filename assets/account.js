document.addEventListener("DOMContentLoaded", function () {
    loadProfile();
    loadAddresses();
    loadOrders();
});



function showSection(sectionId, button) {

    document.querySelectorAll(".section").forEach(function (section) {
        section.classList.remove("active");
    });

    document.querySelectorAll(".menu-item").forEach(function (item) {
        item.classList.remove("active");
    });

    const section = document.getElementById(sectionId);

    if (section) {
        section.classList.add("active");
    }

    if (button) {
        button.classList.add("active");
    }
}


function showMessage(message, type = "success") {

    const box = document.getElementById("message");

    box.textContent = message;
    box.className = "message " + type;

    setTimeout(function () {
        box.className = "message";
        box.textContent = "";
    }, 3000);
}



async function loadProfile() {

    try {

        const response = await fetch("/trang-chu/app/api/profile.php");

        const data = await response.json();

        if (!data.success) {

            showMessage(data.message, "error");
            return;
        }

        const user = data.user;

        document.getElementById("profileName").value =
            user.name || "";

        document.getElementById("profileEmail").value =
            user.email || "";

        document.getElementById("profilePhone").value =
            user.phone || "";

        document.getElementById("sidebarName").textContent =
            "Xin chào, " + (user.name || "bạn");

        if (user.avatar) {

            document.getElementById("avatar").innerHTML =
                `<img src="${escapeHtml(user.avatar)}" alt="Avatar">`;

        }

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể tải thông tin tài khoản.",
            "error"
        );
    }
}


async function updateProfile() {

    const name =
        document.getElementById("profileName").value.trim();

    const phone =
        document.getElementById("profilePhone").value.trim();

    if (name === "") {

        showMessage(
            "Vui lòng nhập họ tên.",
            "error"
        );

        return;
    }

    try {

        const response = await fetch(
            "/trang-chu/app/api/profile.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    name: name,
                    phone: phone
                })
            }
        );

        const data = await response.json();

        if (data.success) {

            showMessage(data.message);

            document.getElementById("sidebarName").textContent =
                "Xin chào, " + name;

        } else {

            showMessage(data.message, "error");
        }

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể cập nhật thông tin.",
            "error"
        );
    }
}


async function changePassword() {

    const currentPassword =
        document.getElementById("currentPassword").value;

    const newPassword =
        document.getElementById("newPassword").value;

    const confirmPassword =
        document.getElementById("confirmPassword").value;

    if (
        currentPassword === "" ||
        newPassword === "" ||
        confirmPassword === ""
    ) {

        showMessage(
            "Vui lòng nhập đầy đủ thông tin.",
            "error"
        );

        return;
    }

    try {

        const response = await fetch(
            "/trang-chu/app/api/change-password.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    currentPassword,
                    newPassword,
                    confirmPassword
                })
            }
        );

        const data = await response.json();

        if (data.success) {

            showMessage(data.message);

            document.getElementById("currentPassword").value = "";
            document.getElementById("newPassword").value = "";
            document.getElementById("confirmPassword").value = "";

        } else {

            showMessage(data.message, "error");
        }

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể đổi mật khẩu.",
            "error"
        );
    }
}

async function loadAddresses() {

    try {

        const response = await fetch(
            "/trang-chu/app/api/addresses.php"
        );

        const data = await response.json();

        if (!data.success) {

            showMessage(data.message, "error");
            return;
        }

        renderAddresses(data.addresses);

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể tải danh sách địa chỉ.",
            "error"
        );
    }
}


function renderAddresses(addresses) {

    const list =
        document.getElementById("addressList");

    if (addresses.length === 0) {

        list.innerHTML = `
            <div class="card">
                <p>Chưa có địa chỉ nào.</p>
            </div>
        `;

        return;
    }

    list.innerHTML = addresses.map(function (address) {

        const fullAddress = [
            address.address_detail,
            address.ward,
            address.district,
            address.city
        ]
        .filter(Boolean)
        .join(", ");

        return `
            <div class="address-card ${address.is_default == 1 ? "default" : ""}">

                <div class="address-top">

                    <strong>
                        ${escapeHtml(address.receiver_name)}
                    </strong>

                    ${
                        address.is_default == 1
                        ? `<span class="default-label">
                            Mặc định
                           </span>`
                        : ""
                    }

                </div>

                <div class="address-info">

                    <div>
                        SĐT:
                        ${escapeHtml(address.receiver_phone)}
                    </div>

                    <div>
                        ${escapeHtml(fullAddress)}
                    </div>

                </div>

                <div class="address-actions">

                    <button
                        class="small-button"
                        onclick='editAddress(${JSON.stringify(address)})'
                    >
                        Sửa
                    </button>

                    ${
                        address.is_default != 1
                        ? `
                        <button
                            class="small-button"
                            onclick="setDefaultAddress(${address.id})"
                        >
                            Đặt mặc định
                        </button>
                        `
                        : ""
                    }

                    <button
                        class="small-button delete-button"
                        onclick="deleteAddress(${address.id})"
                    >
                        Xóa
                    </button>

                </div>

            </div>
        `;

    }).join("");
}


function openAddressForm(address = null) {

    document
        .getElementById("addressForm")
        .classList.remove("hidden");

    if (address) {

        document.getElementById("addressFormTitle")
            .textContent = "Sửa địa chỉ";

        document.getElementById("addressId")
            .value = address.id;

        document.getElementById("receiverName")
            .value = address.receiver_name || "";

        document.getElementById("receiverPhone")
            .value = address.receiver_phone || "";

        document.getElementById("addressDetail")
            .value = address.address_detail || "";

        document.getElementById("ward")
            .value = address.ward || "";

        document.getElementById("district")
            .value = address.district || "";

        document.getElementById("city")
            .value = address.city || "";

        document.getElementById("isDefault")
            .checked = address.is_default == 1;

    } else {

        clearAddressForm();

    }
}


function closeAddressForm() {

    document
        .getElementById("addressForm")
        .classList.add("hidden");

    clearAddressForm();
}


function clearAddressForm() {

    document.getElementById("addressFormTitle")
        .textContent = "Thêm địa chỉ";

    document.getElementById("addressId").value = "";

    document.getElementById("receiverName").value = "";
    document.getElementById("receiverPhone").value = "";
    document.getElementById("addressDetail").value = "";
    document.getElementById("ward").value = "";
    document.getElementById("district").value = "";
    document.getElementById("city").value = "";

    document.getElementById("isDefault").checked = false;
}


function editAddress(address) {

    openAddressForm(address);
}


async function saveAddress() {

    const id =
        document.getElementById("addressId").value;

    const data = {

        receiver_name:
            document.getElementById("receiverName").value.trim(),

        receiver_phone:
            document.getElementById("receiverPhone").value.trim(),

        address_detail:
            document.getElementById("addressDetail").value.trim(),

        ward:
            document.getElementById("ward").value.trim(),

        district:
            document.getElementById("district").value.trim(),

        city:
            document.getElementById("city").value.trim(),

        is_default:
            document.getElementById("isDefault").checked

    };


    if (
        data.receiver_name === "" ||
        data.receiver_phone === "" ||
        data.address_detail === ""
    ) {

        showMessage(
            "Vui lòng nhập đầy đủ thông tin địa chỉ.",
            "error"
        );

        return;
    }


    let url = "/trang-chu/app/api/add-address.php";

    if (id) {

        url = "/trang-chu/app/api/update-address.php";
        data.id = Number(id);

    }


    try {

        const response = await fetch(
            url,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            }
        );

        const result = await response.json();

        if (result.success) {

            showMessage(result.message);

            closeAddressForm();

            loadAddresses();

        } else {

            showMessage(
                result.message,
                "error"
            );
        }

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể lưu địa chỉ.",
            "error"
        );
    }
}


async function deleteAddress(id) {

    if (!confirm("Bạn có chắc muốn xóa địa chỉ này?")) {
        return;
    }

    try {

        const response = await fetch(
            "/trang-chu/app/api/delete-address.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id
                })
            }
        );

        const data = await response.json();

        if (data.success) {

            showMessage(data.message);

            loadAddresses();

        } else {

            showMessage(
                data.message,
                "error"
            );
        }

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể xóa địa chỉ.",
            "error"
        );
    }
}


async function setDefaultAddress(id) {

    try {

        const response = await fetch(
            "/trang-chu/app/api/set-default-address.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id
                })
            }
        );

        const data = await response.json();

        if (data.success) {

            showMessage(data.message);

            loadAddresses();

        } else {

            showMessage(
                data.message,
                "error"
            );
        }

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể đặt địa chỉ mặc định.",
            "error"
        );
    }
}


async function loadOrders() {

    try {

        const response = await fetch(
            "/trang-chu/app/api/oders.php"
        );

        const data = await response.json();

        if (!data.success) {

            showMessage(
                data.message,
                "error"
            );

            return;
        }

        renderOrders(data.orders);

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể tải đơn hàng.",
            "error"
        );
    }
}


function renderOrders(orders) {

    const list =
        document.getElementById("orderList");

    if (orders.length === 0) {

        list.innerHTML = `
            <div class="card">
                <p>Bạn chưa có đơn hàng nào.</p>
            </div>
        `;

        return;
    }

    list.innerHTML = orders.map(function (order) {

        return `
            <div class="order-card">

                <div class="order-header">

                    <span class="order-code">
                        ${escapeHtml(order.order_code)}
                    </span>

                    <span class="order-status">
                        ${getStatusText(order.status)}
                    </span>

                </div>

                <div class="order-info">

                    <span>
                        ${formatDate(order.created_at)}
                    </span>

                    <span class="order-total">
                        ${formatMoney(order.total_amount)}
                    </span>

                </div>

                <br>

                <button
                    class="small-button"
                    onclick="viewOrderDetail(${order.id})"
                >
                    Xem chi tiết
                </button>

                ${order.status === "pending" ? `
                    <button class="small-button delete-button" onclick="updateOrderStatus(${order.id}, 'cancelled')">
                        Hủy đơn hàng
                    </button>
                ` : ""}

                ${order.status === "shipping" ? `
                    <button class="small-button received-button" onclick="updateOrderStatus(${order.id}, 'completed')">
                        Đã nhận hàng
                    </button>
                ` : ""}

            </div>
        `;

    }).join("");
}

async function updateOrderStatus(orderId, status) {
    if (status === "cancelled") {
        openCancelOrderModal(orderId);
        return;
    }

    await submitOrderStatus(orderId, status);
}

function openCancelOrderModal(orderId) {
    const modal = document.getElementById("orderActionModal");
    modal.dataset.orderId = orderId;
    modal.dataset.status = "cancelled";
    document.getElementById("orderActionModalTitle").textContent = "Xác nhận hủy đơn hàng";
    document.getElementById("orderActionModalMessage").textContent = "Bạn có chắc muốn hủy đơn hàng này không? Đơn đã hủy sẽ được lưu trong lịch sử đơn hàng.";
    document.getElementById("confirmOrderActionBtn").textContent = "Xác nhận hủy";
    document.getElementById("confirmOrderActionBtn").classList.remove("hidden");
    modal.classList.remove("hidden");
}

function closeOrderActionModal() {
    const modal = document.getElementById("orderActionModal");
    modal.classList.add("hidden");
    delete modal.dataset.orderId;
    delete modal.dataset.status;
}

async function submitOrderStatus(orderId, status) {

    try {
        const response = await fetch("/trang-chu/app/api/update-order-status.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ order_id: orderId, status })
        });
        const data = await response.json();
        if (!response.ok || !data.success) {
            throw new Error(data.message || "Không thể cập nhật đơn hàng.");
        }
        showMessage(data.message);
        await loadOrders();
    } catch (error) {
        console.error(error);
        showMessage(error.message || "Không thể cập nhật đơn hàng.", "error");
    }
}


async function viewOrderDetail(id) {

    try {

        const response = await fetch(
            "/trang-chu/app/api/oder-detail.php?id=" + id
        );

        const data = await response.json();

        if (!data.success) {

            showMessage(
                data.message,
                "error"
            );

            return;
        }

        const order = data.order;

        const modal = document.getElementById("orderActionModal");
        modal.dataset.mode = "detail";
        document.getElementById("orderActionModalTitle").textContent = "Chi tiết đơn hàng";
        document.getElementById("orderActionModalMessage").innerHTML = `
            <strong>Mã đơn:</strong> ${escapeHtml(order.order_code)}<br>
            <strong>Trạng thái:</strong> ${escapeHtml(getStatusText(order.status))}<br>
            <strong>Tổng tiền:</strong> ${escapeHtml(formatMoney(order.total_amount))}<br>
            <strong>Ngày đặt:</strong> ${escapeHtml(formatDate(order.created_at))}
        `;
        document.getElementById("orderActionModalIcon").innerHTML = '<i class="fa-solid fa-receipt" aria-hidden="true"></i>';
        document.getElementById("confirmOrderActionBtn").classList.add("hidden");
        modal.classList.remove("hidden");

    } catch (error) {

        console.error(error);

        showMessage(
            "Không thể tải chi tiết đơn hàng.",
            "error"
        );
    }
}


async function logout() {
    const modal = document.getElementById("logoutModal");
    modal.classList.remove("hidden");
}

async function confirmLogout() {
    const modal = document.getElementById("logoutModal");
    const button = document.getElementById("confirmLogoutBtn");
    button.disabled = true;

    try {

        const response = await fetch(
            "/trang-chu/app/api/logout.php",
            {
                method: "POST"
            }
        );

        const data = await response.json();

        if (data.success) {

            window.location.href =
                "/trang-chu/index.php";

        } else {

            showMessage(
                data.message,
                "error"
            );
            button.disabled = false;
        }

    } catch (error) {

        console.error(error);

        window.location.href =
            "/trang-chu/index.php";
    }
}

document.querySelectorAll("[data-close-logout-modal]").forEach(function (element) {
    element.addEventListener("click", function () {
        document.getElementById("logoutModal").classList.add("hidden");
    });
});

document.getElementById("confirmLogoutBtn").addEventListener("click", confirmLogout);

document.querySelectorAll("[data-close-order-modal]").forEach(function (element) {
    element.addEventListener("click", closeOrderActionModal);
});

document.getElementById("confirmOrderActionBtn").addEventListener("click", async function () {
    const modal = document.getElementById("orderActionModal");
    const orderId = Number(modal.dataset.orderId);
    const status = modal.dataset.status;
    closeOrderActionModal();
    if (orderId && status) {
        await submitOrderStatus(orderId, status);
    }
});


function formatMoney(value) {

    return Number(value).toLocaleString(
        "vi-VN"
    ) + " ₫";
}


function formatDate(date) {

    if (!date) {
        return "";
    }

    const d = new Date(
        date.replace(" ", "T")
    );

    return d.toLocaleDateString("vi-VN");
}


function getStatusText(status) {

    const statusMap = {

        pending: "Chờ xác nhận",

        confirmed: "Đã xác nhận",

        shipping: "Đang giao",

        completed: "Giao thành công",

        cancelled: "Đã hủy"

    };

    return statusMap[status] || status;
}


function escapeHtml(text) {

    if (text === null || text === undefined) {
        return "";
    }

    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}