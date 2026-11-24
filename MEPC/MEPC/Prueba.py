from selenium import webdriver
from selenium.webdriver.common.by import By

# Configura el navegador (usa ChromeDriver como ejemplo)
driver = webdriver.Chrome(executable_path="ruta/a/chromedriver.exe")

# Abre la página en el servidor local
driver.get("http://localhost/proyecto_ing/proyecto_ing_software/Index.html")

# Interactúa con los elementos del formulario
driver.find_element(By.ID, "username").send_keys("admin")
driver.find_element(By.ID, "password").send_keys("123456")
driver.find_element(By.ID, "login-button").click()

# Verifica el resultado
assert "Bienvenido" in driver.page_source, "Error: Inicio de sesión fallido"

print("Prueba completada: Inicio de sesión exitoso")

# Cierra el navegador
driver.quit()
