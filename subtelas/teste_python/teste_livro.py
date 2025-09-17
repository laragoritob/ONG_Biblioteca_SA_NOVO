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
driver.get("http://localhost:8080/ONG_Biblioteca_SA_NOVO/subtelas/registrar_livro.php")

time.sleep(1)

# Preenche o campo Id do autor
titulo_input = driver.find_element(By.ID, "titulo")
titulo_input.clear()
titulo_input.send_keys("Os Mais Mais++")
time.sleep(1)

# Preenche o campo id do autor
autor_input = driver.find_element(By.ID, "autor")
autor_input.clear()
autor_input.send_keys("Machado de Assis")
time.sleep(1)

# Preenche o campo id do autor
lancamento_input = driver.find_element(By.ID, "data_lancamento")
lancamento_input.clear()
lancamento_input.send_keys("14111999")
time.sleep(1)

genero_select = Select(driver.find_element(By.ID, "cod_genero"))
genero_select.select_by_value("2")
time.sleep(1)

# Preenche o campo Id do autor
editora_input = driver.find_element(By.ID, "nome_editora")
editora_input.clear()
editora_input.send_keys("Editora Objetiva")
time.sleep(1)

# Preenche o campo Id do autor
prateleira_input = driver.find_element(By.ID, "num_prateleira")
prateleira_input.clear()
prateleira_input.send_keys("9")
time.sleep(1)

# Preenche o campo Id do autor
qntd_input = driver.find_element(By.ID, "quantidade")
qntd_input.clear()
qntd_input.send_keys("14")
time.sleep(1)

# Preenche o campo Id do autor
doador_input = driver.find_element(By.ID, "nome_doador")
doador_input.clear()
doador_input.send_keys("Frank Ocean")
time.sleep(1)

# Clica no botão de cadastro
cadastro_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
cadastro_button.click()

# Aguarda alguns segundos para verificar o resultado
time.sleep(3)

# Fecha o navegador
driver.quit()