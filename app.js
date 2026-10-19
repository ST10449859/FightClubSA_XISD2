const API_BASE = new URL('api/', window.location.href).toString();

const TOKEN_KEY = 'fightclubsa_token';
const USER_KEY = 'fightclubsa_user';

function getToken() {
    return localStorage.getItem(TOKEN_KEY);
}

function getUser() {
    try {
        return JSON.parse(
            localStorage.getItem(USER_KEY) || 'null'
        );
    } catch {
        return null;
    }
}

function setSession(data) {
    localStorage.setItem(TOKEN_KEY, data.token);
    localStorage.setItem(USER_KEY, JSON.stringify(data.user));
}

function clearSession() {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[char]);
}

function money(value) {
    return new Intl.NumberFormat('en-ZA', {
        style: 'currency',
        currency: 'ZAR'
    }).format(Number(value || 0));
}

function showMessage(message, type = '') {
    const box = document.getElementById('message');

    if (!box) {
        alert(message);
        return;
    }

    box.textContent = message;
    box.className = 'notice' + (type ? ' ' + type : '');
    box.hidden = false;
}

async function api(path, options = {}) {
    const headers = { ...(options.headers || {}) };

    if (options.body && !headers['Content-Type']) {
        headers['Content-Type'] = 'application/json';
    }

    if (getToken()) {
        headers['Authorization'] = 'Bearer ' + getToken();
    }

    const response = await fetch(API_BASE + path, {
        ...options,
        headers
    });

    let data;

    try {
        data = await response.json();
    } catch {
        throw new Error(
            'The server did not return JSON. Check Apache and the PHP error log.'
        );
    }

    if (!response.ok || data.success === false) {
        throw new Error(data.message || 'The request failed.');
    }

    return data;
}

function requireLogin() {
    if (!getToken()) {
        window.location.href = 'login.php';
    }
}

async function logout() {
    try {
        if (getToken()) {
            await api('logout.php', {
                method: 'POST',
                body: '{}'
            });
        }
    } catch (error) {
        console.warn(error.message);
    }

    clearSession();
    window.location.href = 'index.php';
}

function applyTheme(theme) {
    document.body.classList.toggle(
        'dark',
        theme === 'dark'
    );
}

async function loadSavedTheme() {
    applyTheme(getUser()?.theme || 'light');

    if (!getToken()) {
        return;
    }

    try {
        const result = await api('settings.php');
        applyTheme(result.settings?.theme || 'light');
    } catch {
        // Keep the default theme if settings cannot be loaded.
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadSavedTheme();

    document.querySelectorAll('[data-logout]').forEach(button => {
        button.addEventListener('click', logout);
    });
});
