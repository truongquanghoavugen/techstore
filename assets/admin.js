const adminApiUrl = "/trang-chu/app/api/admin-management.php";
const managementState = {
    products: [],
    categories: [],
    users: [],
    orders: [],
    contacts: []
};

let pendingDeleteAction = null;

const logoutBtn = document.getElementById("logoutBtn");

logoutBtn.addEventListener("click", function () {
    document.getElementById("adminLogoutModal").classList.remove("hidden");
});

async function confirmAdminLogout() {
    const button = document.getElementById("confirmAdminLogoutBtn");
    button.disabled = true;

    try {
        const response = await fetch("/trang-chu/app/api/admin-logout.php", { method: "POST" });
        const data = await response.json();
        if (data.success) {
            window.location.href = "/dang-nhap";
        } else {
            showManagementMessage(data.message || "Đăng xuất thất bại.", true);
            button.disabled = false;
        }
    } catch (error) {
        console.error(error);
        showManagementMessage("Không thể kết nối đến máy chủ.", true);
        button.disabled = false;
    }
}

document.querySelectorAll("[data-close-admin-logout]").forEach(function (element) {
    element.addEventListener("click", function () {
        document.getElementById("adminLogoutModal").classList.add("hidden");
    });
});

document.getElementById("confirmAdminLogoutBtn").addEventListener("click", confirmAdminLogout);

function openDeleteConfirmModal({ title, message, onConfirm }) {
    const modal = document.getElementById("confirmDeleteModal");
    const titleEl = document.getElementById("confirmDeleteModalTitle");
    const messageEl = document.getElementById("confirmDeleteMessage");
    const confirmBtn = document.getElementById("confirmDeleteBtn");

    titleEl.textContent = title;
    messageEl.textContent = message;
    pendingDeleteAction = onConfirm;
    modal.classList.remove("hidden");
    confirmBtn.focus();
}

function closeDeleteConfirmModal() {
    document.getElementById("confirmDeleteModal").classList.add("hidden");
    pendingDeleteAction = null;
}

document.querySelectorAll("[data-close-delete-confirm]").forEach(function (element) {
    element.addEventListener("click", closeDeleteConfirmModal);
});

document.getElementById("confirmDeleteBtn").addEventListener("click", async function () {
    if (!pendingDeleteAction) {
        closeDeleteConfirmModal();
        return;
    }

    try {
        await pendingDeleteAction();
    } catch (error) {
        console.error(error);
        showManagementMessage(error.message || "Không thể xóa dữ liệu.", true);
    } finally {
        closeDeleteConfirmModal();
    }
});

function escapeHtml(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function formatPrice(value) {
    return Number(value || 0).toLocaleString("vi-VN") + " đ";
}

function showManagementMessage(message, isError = false) {
    const element = document.getElementById("adminManagementMessage");
    element.textContent = message;
    element.style.color = isError ? "#c0392b" : "#287a4b";
}

async function requestAdmin(action, options = {}) {
    const url = `${adminApiUrl}?action=${encodeURIComponent(action)}`;
    const response = await fetch(url, {
        ...options,
        headers: {
            "Content-Type": "application/json",
            ...(options.headers || {})
        }
    });
    const data = await response.json();
    if (!response.ok || !data.success) {
        throw new Error(data.message || "Không thể thực hiện thao tác.");
    }
    return data;
}

async function loadManagementData() {
    try {
        const [products, categories, users, orders, contacts] = await Promise.all([
            requestAdmin("products"),
            requestAdmin("categories"),
            requestAdmin("users"),
            requestAdmin("orders"),
            requestAdmin("contacts")
        ]);

        managementState.products = products.items;
        managementState.categories = categories.items;
        managementState.users = users.items;
        managementState.orders = orders.items;
        managementState.contacts = contacts.items;
        renderProducts();
        renderCategories();
        renderUsers();
        renderOrders();
        renderContacts();
        populateCategoryOptions();
    } catch (error) {
        console.error(error);
        showManagementMessage(error.message, true);
    }
}

function getFilteredProducts() {
    const searchTerm = (document.getElementById("productSearchInput")?.value || "").trim().toLowerCase();
    if (!searchTerm) {
        return managementState.products;
    }

    return managementState.products.filter(product => {
        const searchableText = [
            product.name,
            product.category,
            product.description
        ].filter(Boolean).join(" ").toLowerCase();

        return searchableText.includes(searchTerm);
    });
}

function renderProducts() {
    const body = document.getElementById("productsTableBody");
    const searchResultCount = document.getElementById("productSearchResultCount");
    const filteredProducts = getFilteredProducts();

    if (searchResultCount) {
        searchResultCount.textContent = `${filteredProducts.length} sản phẩm`;
    }

    if (!filteredProducts.length) {
        body.innerHTML = '<tr><td colspan="5" class="muted-cell">Không tìm thấy sản phẩm phù hợp.</td></tr>';
        return;
    }

    body.innerHTML = filteredProducts.map(product => `
        <tr>
            <td><strong>${escapeHtml(product.name)}</strong><small class="muted-cell">#${product.id}</small></td>
            <td>${escapeHtml(product.category || "Chưa phân loại")}</td>
            <td>${formatPrice(product.price)}</td>
            <td>${Number(product.stock)}</td>
            <td class="table-actions">
                <button class="table-action" type="button" data-edit-product="${product.id}"><i class="fa-solid fa-pen" aria-hidden="true"></i> Sửa</button>
                <button class="table-action delete" type="button" data-delete-product="${product.id}"><i class="fa-solid fa-trash" aria-hidden="true"></i> Xóa</button>
            </td>
        </tr>
    `).join("");
}

function renderCategories() {
    const body = document.getElementById("categoriesTableBody");
    if (!managementState.categories.length) {
        body.innerHTML = '<tr><td colspan="3" class="muted-cell">Chưa có danh mục.</td></tr>';
        return;
    }

    body.innerHTML = managementState.categories.map(category => `
        <tr>
            <td><strong>${escapeHtml(category.name)}</strong></td>
            <td>${escapeHtml(category.created_at || "")}</td>
            <td class="table-actions">
                <button class="table-action" type="button" data-edit-category="${category.id}"><i class="fa-solid fa-pen" aria-hidden="true"></i> Sửa</button>
                <button class="table-action delete" type="button" data-delete-category="${category.id}"><i class="fa-solid fa-trash" aria-hidden="true"></i> Xóa</button>
            </td>
        </tr>
    `).join("");
}

function renderUsers() {
    const body = document.getElementById("usersTableBody");
    if (!managementState.users.length) {
        body.innerHTML = '<tr><td colspan="5" class="muted-cell">Chưa có tài khoản.</td></tr>';
        return;
    }

    body.innerHTML = managementState.users.map(user => `
        <tr data-user-row="${user.id}">
            <td><input class="user-name" value="${escapeHtml(user.name)}"></td>
            <td>${escapeHtml(user.email)}</td>
            <td><input class="user-phone" value="${escapeHtml(user.phone || "")}"></td>
            <td>
                <select class="user-role">
                    <option value="user" ${user.role === "user" ? "selected" : ""}>User</option>
                    <option value="admin" ${user.role === "admin" ? "selected" : ""}>Admin</option>
                </select>
            </td>
            <td class="table-actions">
                <button class="table-action" type="button" data-save-user="${user.id}"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Lưu</button>
                <button class="table-action delete" type="button" data-delete-user="${user.id}"><i class="fa-solid fa-trash" aria-hidden="true"></i> Xóa</button>
            </td>
        </tr>
    `).join("");
}

function renderOrders() {
    const body = document.getElementById("ordersTableBody");
    if (!managementState.orders.length) {
        body.innerHTML = '<tr><td colspan="7" class="muted-cell">Chưa có đơn hàng.</td></tr>';
        return;
    }

    body.innerHTML = managementState.orders.map(order => `
        <tr>
            <td><strong>${escapeHtml(order.order_code)}</strong></td>
            <td>${escapeHtml(order.customer_name)}</td>
            <td>${escapeHtml(order.email)}</td>
            <td>${formatPrice(order.total_amount)}</td>
            <td><span class="order-status-badge status-${escapeHtml(order.status)}">${escapeHtml(getAdminStatusText(order.status))}</span></td>
            <td>${escapeHtml(order.created_at)}</td>
            <td class="table-actions">${getOrderActionButton(order)}</td>
        </tr>
    `).join("");
}

function getAdminStatusText(status) {
    return {
        pending: "Chờ xác nhận",
        confirmed: "Đã xác nhận",
        shipping: "Đang giao",
        completed: "Giao thành công",
        cancelled: "Đã hủy"
    }[status] || status;
}

function renderContacts() {
    const body = document.getElementById("contactsTableBody");
    if (!managementState.contacts.length) {
        body.innerHTML = '<tr><td colspan="6" class="muted-cell">Chưa có tin nhắn liên hệ.</td></tr>';
        return;
    }

    body.innerHTML = managementState.contacts.map(contact => `
        <tr>
            <td><strong>${escapeHtml(contact.full_name)}</strong></td>
            <td>${escapeHtml(contact.email)}<small class="muted-cell">${escapeHtml(contact.phone || "")}</small></td>
            <td>${escapeHtml(contact.subject || "Không có chủ đề")}</td>
            <td class="contact-message-cell">${escapeHtml(contact.message)}</td>
            <td>${escapeHtml(contact.attachment_name || "Không có")}</td>
            <td>${escapeHtml(contact.created_at)}</td>
        </tr>
    `).join("");
}

function getOrderActionButton(order) {
    if (order.status === "pending") {
        return `<button class="table-action" type="button" data-order-action="${order.id}" data-next-status="confirmed"><i class="fa-solid fa-check" aria-hidden="true"></i> Xác nhận</button>`;
    }
    if (order.status === "confirmed") {
        return `<button class="table-action" type="button" data-order-action="${order.id}" data-next-status="shipping"><i class="fa-solid fa-truck" aria-hidden="true"></i> Đang giao</button>`;
    }
    return '<span class="muted-cell">Không có thao tác</span>';
}

async function updateOrderStatus(id, status) {
    await requestAdmin("update_order_status", {
        method: "POST",
        body: JSON.stringify({ order_id: Number(id), status })
    });
    showManagementMessage("Đã cập nhật trạng thái đơn hàng.");
    await loadManagementData();
}

function populateCategoryOptions() {
    document.getElementById("categoryOptions").innerHTML = managementState.categories
        .map(category => `<option value="${escapeHtml(category.name)}">`)
        .join("");
}

function resetProductForm() {
    document.getElementById("productForm").reset();
    document.getElementById("productId").value = "";
    document.getElementById("productForm").classList.add("hidden");
}

function editProduct(id) {
    const product = managementState.products.find(item => Number(item.id) === Number(id));
    if (!product) return;
    document.getElementById("productId").value = product.id;
    document.getElementById("productName").value = product.name || "";
    document.getElementById("productCategory").value = product.category || "";
    document.getElementById("productPrice").value = product.price || 0;
    document.getElementById("productStock").value = product.stock || 0;
    document.getElementById("productImage").value = product.image || "";
    document.getElementById("productDescription").value = product.description || "";
    document.getElementById("productForm").classList.remove("hidden");
    document.getElementById("productName").focus();
}

async function deleteProduct(id) {
    const product = managementState.products.find(item => Number(item.id) === Number(id));
    const productName = product ? product.name : `sản phẩm #${id}`;

    openDeleteConfirmModal({
        title: "Xóa sản phẩm?",
        message: `Bạn có chắc muốn xóa ${productName} không? Hành động này không thể hoàn tác.`,
        onConfirm: async () => {
            await requestAdmin("delete_product", {
                method: "POST",
                body: JSON.stringify({ id: Number(id) })
            });
            showManagementMessage("Đã xóa sản phẩm.");
            await loadManagementData();
        }
    });
}

async function deleteCategory(id) {
    const category = managementState.categories.find(item => Number(item.id) === Number(id));
    const categoryName = category ? category.name : `danh mục #${id}`;

    openDeleteConfirmModal({
        title: "Xóa danh mục?",
        message: `Xóa ${categoryName} sẽ bỏ phân loại của các sản phẩm liên quan. Tiếp tục?`,
        onConfirm: async () => {
            await requestAdmin("delete_category", {
                method: "POST",
                body: JSON.stringify({ id })
            });
            showManagementMessage("Đã xóa danh mục.");
            await loadManagementData();
        }
    });
}

async function saveUser(row, id) {
    await requestAdmin("save_user", {
        method: "POST",
        body: JSON.stringify({
            id,
            name: row.querySelector(".user-name").value.trim(),
            phone: row.querySelector(".user-phone").value.trim(),
            role: row.querySelector(".user-role").value
        })
    });
    showManagementMessage("Đã cập nhật tài khoản.");
    await loadManagementData();
}

async function deleteUser(id) {
    const user = managementState.users.find(item => Number(item.id) === Number(id));
    const userName = user ? user.name : `tài khoản #${id}`;

    openDeleteConfirmModal({
        title: "Xóa tài khoản?",
        message: `Bạn có chắc muốn xóa ${userName} không?`,
        onConfirm: async () => {
            await requestAdmin("delete_user", {
                method: "POST",
                body: JSON.stringify({ id })
            });
            showManagementMessage("Đã xóa tài khoản.");
            await loadManagementData();
        }
    });
}

document.querySelectorAll("[data-admin-tab]").forEach(button => {
    button.addEventListener("click", function () {
        const tab = this.dataset.adminTab;
        document.querySelectorAll("[data-admin-tab]").forEach(item => item.classList.toggle("active", item === this));
        document.querySelectorAll("[data-admin-panel]").forEach(panel => panel.classList.toggle("active", panel.dataset.adminPanel === tab));
    });
});

document.getElementById("newProductBtn").addEventListener("click", function () {
    document.getElementById("productForm").classList.remove("hidden");
    document.getElementById("productName").focus();
});

document.getElementById("cancelProductBtn").addEventListener("click", resetProductForm);

document.getElementById("productForm").addEventListener("submit", async function (event) {
    event.preventDefault();
    try {
        await requestAdmin("save_product", {
            method: "POST",
            body: JSON.stringify({
                id: document.getElementById("productId").value,
                name: document.getElementById("productName").value.trim(),
                category: document.getElementById("productCategory").value.trim(),
                price: document.getElementById("productPrice").value,
                stock: document.getElementById("productStock").value,
                image: document.getElementById("productImage").value.trim(),
                description: document.getElementById("productDescription").value.trim()
            })
        });
        resetProductForm();
        showManagementMessage("Đã lưu sản phẩm.");
        await loadManagementData();
    } catch (error) {
        showManagementMessage(error.message, true);
    }
});

document.getElementById("categoryForm").addEventListener("submit", async function (event) {
    event.preventDefault();
    try {
        await requestAdmin("save_category", {
            method: "POST",
            body: JSON.stringify({
                id: document.getElementById("categoryId").value,
                name: document.getElementById("categoryName").value.trim()
            })
        });
        this.reset();
        document.getElementById("categoryId").value = "";
        document.getElementById("cancelCategoryBtn").classList.add("hidden");
        showManagementMessage("Đã lưu danh mục.");
        await loadManagementData();
    } catch (error) {
        showManagementMessage(error.message, true);
    }
});

document.getElementById("cancelCategoryBtn").addEventListener("click", function () {
    document.getElementById("categoryForm").reset();
    document.getElementById("categoryId").value = "";
    this.classList.add("hidden");
});

document.getElementById("productSearchInput")?.addEventListener("input", function () {
    renderProducts();
});

document.addEventListener("click", async function (event) {
    const editProductButton = event.target.closest("[data-edit-product]");
    const deleteProductButton = event.target.closest("[data-delete-product]");
    const editCategoryButton = event.target.closest("[data-edit-category]");
    const deleteCategoryButton = event.target.closest("[data-delete-category]");
    const saveUserButton = event.target.closest("[data-save-user]");
    const deleteUserButton = event.target.closest("[data-delete-user]");
    const orderActionButton = event.target.closest("[data-order-action]");

    try {
        if (editProductButton) editProduct(editProductButton.dataset.editProduct);
        if (deleteProductButton) await deleteProduct(deleteProductButton.dataset.deleteProduct);
        if (editCategoryButton) {
            const category = managementState.categories.find(item => Number(item.id) === Number(editCategoryButton.dataset.editCategory));
            if (category) {
                document.getElementById("categoryId").value = category.id;
                document.getElementById("categoryName").value = category.name;
                document.getElementById("cancelCategoryBtn").classList.remove("hidden");
            }
        }
        if (deleteCategoryButton) await deleteCategory(deleteCategoryButton.dataset.deleteCategory);
        if (saveUserButton) await saveUser(saveUserButton.closest("tr"), saveUserButton.dataset.saveUser);
        if (deleteUserButton) await deleteUser(deleteUserButton.dataset.deleteUser);
        if (orderActionButton) await updateOrderStatus(orderActionButton.dataset.orderAction, orderActionButton.dataset.nextStatus);
    } catch (error) {
        showManagementMessage(error.message, true);
    }
});

loadManagementData();