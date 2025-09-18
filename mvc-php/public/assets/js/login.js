document.getElementById("loginForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const correo = document.getElementById("correo").value.trim();
    const clave = document.getElementById("clave").value.trim();
    const mensaje = document.getElementById("mensaje");

    // Reset mensajes
    mensaje.innerText = "";
    mensaje.className = "";

    if (!correo || !clave) {
        mensaje.innerText = "⚠️ Por favor, complete todos los campos.";
        mensaje.className = "error";
        return;
    }

    mensaje.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Validando credenciales...`;
    mensaje.className = "loading";

    try {
        const response = await fetch("/Proyecto_Grupal/PHP-GRUPAL/mvc-php/public/api/LoginService.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ correo, clave })
        });

        const data = await response.json();
        console.log("Respuesta del servidor:", data);

        if (data.success) {
            mensaje.innerHTML = `<i class="fa fa-check-circle"></i> Bienvenido ${data.rol}`;
            mensaje.className = "success";

            // 👉 Guardar datos en localStorage (depuración temporal)
            localStorage.setItem("usuarioRol", data.rol);
            localStorage.setItem("usuarioCorreo", correo);

            // 👉 Redirigir según el rol
            setTimeout(() => {
                if (data.rol === "Administrador") {
                    window.location.href = "/Proyecto_Grupal/PHP-GRUPAL/mvc-php/public/views/usuario/admin.php";
                } else if (data.rol === "Vendedor") {
                    window.location.href = "/Proyecto_Grupal/PHP-GRUPAL/mvc-php/public/views/usuario/vendedor.php";
                }
            }, 1200);
        } else {
            mensaje.innerHTML = `<i class="fa fa-times-circle"></i> ${data.message}`;
            mensaje.className = "error";
        }
    } catch (error) {
        console.error("Error en el fetch:", error);
        mensaje.innerHTML = `<i class="fa fa-exclamation-triangle"></i> Error de conexión con el servidor.`;
        mensaje.className = "error";
    }
});

window.addEventListener("DOMContentLoaded", () => {
    const rol = localStorage.getItem("usuarioRol");
    const correo = localStorage.getItem("usuarioCorreo");

    console.log("Usuario logueado:", rol, correo);

    if (window.location.pathname.includes("admin.php") && rol !== "Administrador") {
        alert("No tienes permisos para entrar aquí");
        window.location.href = "/Proyecto_Grupal/PHP-GRUPAL/mvc-php/public/views/usuario/login.php";
    }
});
