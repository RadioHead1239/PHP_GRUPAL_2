document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const correo = document.getElementById("correo").value;
    const clave = document.getElementById("clave").value;
    const mensaje = document.getElementById("mensaje");

    try {
        const response = await fetch("login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ correo, clave })
        });

        const data = await response.json();

        if (data.success) {
            if (data.rol === "Administrador") {
                window.location.href = "admin.php";
            } else if (data.rol === "Vendedor") {
                window.location.href = "vendedor.php";
            }
        } else {
            mensaje.innerText = data.message;
        }
    } catch (error) {
        mensaje.innerText = "Error de conexión con el servidor.";
    }
});