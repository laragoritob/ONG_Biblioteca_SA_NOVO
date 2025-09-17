from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
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
driver.get("http://localhost:8080/ONG_Biblioteca_SA_NOVO/subtelas/registrar_autor.php")

time.sleep(1)

# Preenche o campo Id do autor
autor_input = driver.find_element(By.ID, "titulo")
autor_input.clear()
autor_input.send_keys("Os Mais Mais++")
time.sleep(2)

# Preenche o campo id do telefone
telefone_input = driver.find_element(By.ID, "nome_autor")
telefone_input.clear()
telefone_input.send_keys("Emanuel da Silva")
time.sleep(2)

# Preenche o campo id do telefone
email_input = driver.find_element(By.ID, "data_lancamento")
email_input.clear()
email_input.send_keys("14-11-1999")
time.sleep(2)

perfil_select = Select(driver.find_element(By.ID, "genero"))
perfil_select.select_by_value("2")
time.sleep(2)

# Preenche o campo Id do autor
autor_input = driver.find_element(By.ID, "nome_editora")
autor_input.clear()
autor_input.send_keys("Editora Objetiva")
time.sleep(2)

# Preenche o campo Id do autor
autor_input = driver.find_element(By.ID, "num_prateleira")
autor_input.clear()
autor_input.send_keys("9")
time.sleep(2)

# Preenche o campo Id do autor
autor_input = driver.find_element(By.ID, "quantidade")
autor_input.clear()
autor_input.send_keys("14")
time.sleep(2)

# Preenche o campo Id do autor
autor_input = driver.find_element(By.ID, "nome_doador")
autor_input.clear()
autor_input.send_keys("Frank Ocean")
time.sleep(2)

# Clica no botão de cadastro
cadastro_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
cadastro_button.click()

# Aguarda alguns segundos para verificar o resultado
time.sleep(4)

# Fecha o navegador
driver.quit()