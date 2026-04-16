<customer-auth data-auth-sidebar="" class="halo-sidebar halo-sidebar-right halo-auth-sidebar"><div class="halo-sidebar-header style-2 text-center">
		<span class="title">Inicio de sesión
</span><a href="#" class="close" data-close-auth-sidebar="" role="button">
                <span class="visually-hidden">Cerrar</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" aria-hidden="true" focusable="false" role="presentation">
	<path d="M 38.982422 6.9707031 A 2.0002 2.0002 0 0 0 37.585938 7.5859375 L 24 21.171875 L 10.414062 7.5859375 A 2.0002 2.0002 0 0 0 8.9785156 6.9804688 A 2.0002 2.0002 0 0 0 7.5859375 10.414062 L 21.171875 24 L 7.5859375 37.585938 A 2.0002 2.0002 0 1 0 10.414062 40.414062 L 24 26.828125 L 37.585938 40.414062 A 2.0002 2.0002 0 1 0 40.414062 37.585938 L 26.828125 24 L 40.414062 10.414062 A 2.0002 2.0002 0 0 0 38.982422 6.9707031 z"></path>
</svg>
            </a></div>
    
<div>
<form accept-charset="UTF-8" action="/account/login" method="post" class="auth-form auth-form-2">
                <input name="form_type" type="hidden" value="customer_login">
                <input name="utf8" type="hidden" value="✓">
                <div class="form-field">
                    <label class="form-label" for="customer_email">
                        Correo electrónico
                        <em>*</em>
                    </label>
                    <input class="form-input form-input-placeholder" type="email" value="" name="customer[email]" placeholder="Correo electrónico">
                </div>
                <div class="form-field">
                    <label class="form-label" for="customer_password">
                        Contraseña
                        <em>*</em>
                    </label>
                    <input class="form-input form-input-placeholder" type="password" value="" placeholder="Contraseña" name="customer[password]">
                </div>
                <div class="form-actions auth-actions text-center">
                    <input type="submit" class="button button-height button-5 button-login" value="Iniciar sesión">
                    <a href="/account/register" class="button button-height flex align-center justify-center button-5 button-register">
                        Registrarse
                    </a>
                    <a class="auth-link link link-underline" href="/account/login#recover">
                        <span class="text"></span>
                    </a>
                </div>
            </form></div></customer-auth>