import { login, signup, logout, getUser, getSettings, handleAuthCallback, requestPasswordRecovery, updateUser, acceptInvite, onAuthChange, refreshSession } from '@netlify/identity';

const root = document.querySelector('#siac-auth');
const callbackKeys = ['confirmation_token', 'recovery_token', 'invite_token', 'access_token', 'email_change_token', 'error', 'error_description'];
const hash = new URLSearchParams(location.hash.slice(1));
if (!root && callbackKeys.some(key => hash.has(key))) {
    location.replace('/login' + location.hash);
} else {
    start().catch(() => {
        if (root) message('El servicio de acceso no está disponible. Recarga la página e inténtalo de nuevo.', true);
    });
}

function message(text, error = false) {
    const status = document.querySelector('#auth-status');
    if (status) {
        status.textContent = text;
        status.dataset.error = String(error);
    }
}

async function start() {
    const nav = user => document.querySelectorAll('[data-account-link]').forEach(link => {
        link.textContent = user ? 'Mi cuenta' : 'Iniciar sesión';
    });
    onAuthChange(async () => {
        const user = await getUser();
        nav(user);
        if (root && mode !== 'reset' && mode !== 'invite') showSession(user);
    });
    let mode = 'login';
    let inviteToken;
    let allowSignup = false;
    const form = document.querySelector('#auth-form');
    const options = document.querySelector('#auth-options');
    const session = document.querySelector('#auth-session');
    const email = document.querySelector('#auth-email');
    const password = document.querySelector('#auth-password');
    const submit = document.querySelector('#auth-submit');
    const titles = { login: 'Iniciar sesión', signup: 'Crear cuenta', recovery: 'Recuperar contraseña', reset: 'Nueva contraseña', invite: 'Aceptar invitación' };

    function setMode(next) {
        mode = next;
        form.hidden = false;
        session.hidden = true;
        options.hidden = next === 'reset' || next === 'invite';
        document.querySelector('#auth-title').textContent = titles[next];
        const needsEmail = next !== 'reset' && next !== 'invite';
        document.querySelector('#auth-email-group').hidden = !needsEmail;
        email.required = needsEmail;
        document.querySelector('#auth-password-group').hidden = next === 'recovery';
        password.required = next !== 'recovery';
        password.value = '';
        password.autocomplete = next === 'login' ? 'current-password' : 'new-password';
        password.minLength = next === 'login' ? 1 : 8;
        document.querySelector('#auth-password-hint').hidden = next === 'login' || next === 'recovery';
        submit.textContent = next === 'login' ? 'Ingresar' : next === 'recovery' ? 'Enviar enlace' : titles[next];
        message('');
    }
    function showSession(user) {
        if (!root) return;
        if (!user) { setMode('login'); return; }
        form.hidden = true;
        options.hidden = true;
        session.hidden = false;
        document.querySelector('#auth-title').textContent = 'Mi cuenta';
        document.querySelector('#auth-user').textContent = user.email;
        message('Tu sesión está activa.');
    }
    const user = await getUser();
    nav(user);
    if (!root) return;

    // Process email links even when loading settings fails temporarily.
    let callback;
    try {
        callback = await handleAuthCallback();
    } catch {
        history.replaceState(null, '', location.pathname + location.search);
        message('El enlace no es válido o ha vencido. Solicita uno nuevo.', true);
    }
    try {
        const settings = await getSettings();
        allowSignup = !settings.disableSignup;
        document.querySelector('#auth-signup').hidden = !allowSignup;
    } catch {
        // Login and recovery remain available; signup requires confirmed settings.
    }
    const callbackFailed = document.querySelector('#auth-status').dataset.error === 'true';
    if (callback?.type === 'recovery' || callback?.type === 'invite') {
        inviteToken = callback.token;
        setMode(callback.type === 'invite' ? 'invite' : 'reset');
    } else {
        showSession(await getUser());
        if (callbackFailed) message('El enlace no es válido o ha vencido. Solicita uno nuevo.', true);
    }

    options.addEventListener('click', event => {
        const button = event.target.closest('[data-auth-mode]');
        if (!button || (button.dataset.authMode === 'signup' && !allowSignup)) return;
        setMode(button.dataset.authMode);
        email.focus();
    });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const action = mode;
        submit.disabled = true;
        options.querySelectorAll('button').forEach(button => { button.disabled = true; });
        form.setAttribute('aria-busy', 'true');
        message('Procesando…');
        try {
            if (action === 'login') {
                showSession(await login(email.value.trim(), password.value));
            } else if (action === 'signup') {
                const created = await signup(email.value.trim(), password.value);
                if (created.emailVerified) showSession(await getUser());
                else { setMode('login'); message('Revisa tu correo y confirma tu cuenta antes de ingresar.'); }
            } else if (action === 'recovery') {
                await requestPasswordRecovery(email.value.trim());
                message('Si existe una cuenta con ese correo, recibirás un enlace para cambiar la contraseña.');
            } else {
                const updated = action === 'invite' ? await acceptInvite(inviteToken, password.value) : await updateUser({ password: password.value });
                inviteToken = undefined;
                mode = 'login';
                showSession(updated);
                message('Contraseña guardada. Tu sesión está activa.');
            }
        } catch (error) {
            if (action === 'login') message('No se pudo iniciar sesión. Revisa el correo y la contraseña y confirma tu cuenta desde el correo recibido.', true);
            else if (error.status === 429) message('Demasiados intentos. Espera unos minutos e inténtalo de nuevo.', true);
            else if (error.status === 403 && action === 'signup') message('El registro está cerrado. Solicita una invitación al responsable del sitio.', true);
            else message('No se pudo completar la solicitud. Revisa los datos o inténtalo de nuevo más tarde.', true);
        } finally {
            password.value = '';
            submit.disabled = false;
            options.querySelectorAll('button').forEach(button => { button.disabled = false; });
            form.removeAttribute('aria-busy');
        }
    });
    setupUpload();
    document.querySelector('#auth-logout').addEventListener('click', async event => {
        const button = event.currentTarget;
        button.disabled = true;
        try { await logout(); showSession(null); message('Has cerrado sesión.'); }
        catch { showSession(await getUser()); message('No se pudo completar el cierre de sesión. Inténtalo de nuevo.', true); }
        finally { button.disabled = false; }
    });
}

function setupUpload() {
    const form = document.querySelector('#upload-form');
    if (!form) return;
    const status = document.querySelector('#upload-status');
    const submit = document.querySelector('#upload-submit');
    const fileInput = document.querySelector('#upload-file');
    const titleInput = document.querySelector('#upload-title-input');
    const say = (text, error = false) => { status.textContent = text; status.dataset.error = String(error); };
    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file && !titleInput.value.trim()) titleInput.value = file.name.replace(/\.[^.]+$/, '');
    });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const file = fileInput.files[0];
        if (file && file.size > 4 * 1024 * 1024) { say('El archivo supera el tamaño máximo de 4 MB.', true); return; }
        submit.disabled = true;
        form.setAttribute('aria-busy', 'true');
        say('Subiendo documento…');
        try {
            // Keep the nf_jwt cookie (used by the function to authenticate the request) fresh.
            await refreshSession();
            if (!(await getUser())) { say('Tu sesión venció. Vuelve a iniciar sesión.', true); return; }
            const res = await fetch('/api/documentos/subir', { method: 'POST', body: new FormData(form), credentials: 'same-origin' });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) { say(data.error || 'No se pudo subir el documento. Inténtalo de nuevo.', true); return; }
            form.reset();
            status.replaceChildren('Documento «' + data.title + '» publicado. ');
            const link = document.createElement('a');
            link.href = '/documentos';
            link.textContent = 'Ver documentos';
            status.append(link);
            status.dataset.error = 'false';
        } catch {
            say('No se pudo subir el documento. Revisa tu conexión e inténtalo de nuevo.', true);
        } finally {
            submit.disabled = false;
            form.removeAttribute('aria-busy');
        }
    });
}
