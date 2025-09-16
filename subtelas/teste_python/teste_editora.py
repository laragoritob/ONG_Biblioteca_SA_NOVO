from selenium import webdriver
from selenium.webdriver.common.by import By
import time

driver = webdriver.Chrome()

driver.get("http://localhost:8080/ONG_Biblioteca_SA_NOVO/index.php")

time.sleep(2)

# Preenche os campos
driver.find_element(By.ID,"usuario").send_keys("sergio_luiz")
time.sleep(1)
driver.find_element(By.ID,"senha").send_keys("12345678")
time.sleep(2)
driver.find_element(By.CSS_SELECTOR, "button[type='submit']"). click()

time.sleep(1)

# Navega para a página de Fazer Empréstimo
driver.get("http://localhost:8080/ONG_Biblioteca_SA_NOVO/subtelas/registrar_editora.php")

time.sleep(1)

# Preenche o campo Id do editora
editora_input = driver.find_element(By.ID, "nome_editora")
editora_input.clear()
editora_input.send_keys("Editora Panda Books")
time.sleep(2)

# Preenche o campo id do telefone
telefone_input = driver.find_element(By.ID, "telefone")
telefone_input.clear()
telefone_input.send_keys("47972093459")
time.sleep(2)

# Preenche o campo id do telefone
email_input = driver.find_element(By.ID, "email")
email_input.clear()
email_input.send_keys("pandabooks@gmail.com")
time.sleep(2)

# Clica no botão de cadastro
cadastro_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
cadastro_button.click()

# Aguarda alguns segundos para verificar o resultado
time.sleep(4)

# Fecha o navegador
driver.quit()