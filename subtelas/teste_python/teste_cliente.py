from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

driver = webdriver.Chrome()

driver.get("http://localhost:8080/ONG_Biblioteca_SA_NOVO/index.php")
time.sleep(2)

# Login
driver.find_element(By.ID, "usuario").send_keys("sergio_luiz")
time.sleep(1.5)
driver.find_element(By.ID, "senha").send_keys("12345678")
time.sleep(2)
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
time.sleep(1.5)

# Vai para página de cadastro de funcionário
driver.get("http://localhost:8080/ONG_Biblioteca_SA_NOVO/subtelas/cadastro_cliente.php")
time.sleep(1.5)

# Preenche os campos do formulário
# Preenche o campo NOME
driver.find_element(By.NAME, "nome").send_keys("João Dela da Silva")
time.sleep(1.5)

# Preenche o campo DATA DE NASCIMENTO (yyyy-mm-dd)
driver.find_element(By.NAME, "data_nascimento").send_keys("20011970")
time.sleep(1.5)

# Seleciona sexo
sexo_select = Select(driver.find_element(By.NAME, "sexo"))
sexo_select.select_by_value("Masculino")
time.sleep(1.5)

# Preenche o campo CPF
driver.find_element(By.NAME, "cpf").send_keys("098765432123")
time.sleep(1.5)

# Preenche o campo EMAIL
driver.find_element(By.NAME, "email").send_keys("joao.silva@example.com")
time.sleep(1.5)

# Preenche o campo Telefone
driver.find_element(By.NAME, "telefone").send_keys("47988887778")
time.sleep(1.5)

# Upload da foto (arquivo existente no projeto)
driver.find_element(By.ID, "foto").send_keys(r"C:/xampp/htdocs/ONG_Biblioteca_SA_NOVO/subtelas/subtelas_img/alann.jpg")
time.sleep(2)

# Seleciona perfil (Gestor)
perfil_select = Select(driver.find_element(By.NAME, "perfil"))
perfil_select.select_by_value("2")
time.sleep(1.5)

# Preenche o campo CEP
driver.find_element(By.NAME, "cep").send_keys("89219-510")
# CLCA NO BOTÃO QUE PROCURA O ESTADO, CIDADE, BAIRRO E RUA
driver.find_element(By.CSS_SELECTOR, "button[onclick*='buscarCEP']").click()
# Aguarda o preenchimento automático do CEP (estado/cidade/bairro/rua)
try:
    WebDriverWait(driver, 10).until(lambda d: d.find_element(By.NAME, "uf").get_attribute("value") != "")
    WebDriverWait(driver, 10).until(lambda d: d.find_element(By.NAME, "cidade").get_attribute("value") != "")
    WebDriverWait(driver, 10).until(lambda d: d.find_element(By.NAME, "bairro").get_attribute("value") != "")
    WebDriverWait(driver, 10).until(lambda d: d.find_element(By.NAME, "rua").get_attribute("value") != "")
except Exception:
    time.sleep(2)

    # Preenche o campo NUMERO DA RESIDENCIA
driver.find_element(By.NAME, "num_residencia").send_keys("1525")
time.sleep(1.5)

# Clica no botão de cadastro
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
time.sleep(2)

# Fecha o navegador
driver.quit()
