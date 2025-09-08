from selenium import webdriver
from selenium.webdriver.common.by import By
import time

driver = webdriver.Chrome()

driver.get("http://localhost/001Turma2024_2V1_TARDE/ONG_Biblioteca_SA_NOVO/index.php")

time.sleep(2)

# Preenche os campos
driver.find_element(By.ID,"usuario").send_keys("sergio_luiz")
time.sleep(1)
driver.find_element(By.ID,"senha").send_keys("12345678")
time.sleep(2)
driver.find_element(By.CSS_SELECTOR, "button[type='submit']"). click()

time.sleep(1)

# Navega para a página de Fazer Empréstimo
driver.get("http://localhost/001Turma2024_2V1_TARDE/ONG_Biblioteca_SA_NOVO/subtelas/registrar_emprestimo.php")

time.sleep(1)

# Preenche o campo Id do Livro
livro_input = driver.find_element(By.ID, "cod_livro")
livro_input.clear()
livro_input.send_keys("15")
time.sleep(2)

# Preenche o campo id do cliente
cliente_input = driver.find_element(By.ID, "cod_cliente")
cliente_input.clear()
cliente_input.send_keys("11")
time.sleep(2)

# Preenche o campo Data de Empréstimo
data_emprestimo_input = driver.find_element(By.ID, "data_emprestimo")
data_emprestimo_input.clear()
data_emprestimo_input.send_keys("10-09-2025")
time.sleep(2)

# Clica no botão de cadastro
cadastro_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
cadastro_button.click()

# Aguarda alguns segundos para verificar o resultado
time.sleep(4)

# Fecha o navegador
driver.quit()