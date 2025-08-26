// Função para buscar autor pelo ID
function buscarAutor() {
	const idAutor = document.getElementById('cod_autor').value;
	const campoNomeAutor = document.getElementById('autor');
	
	if (idAutor.trim() === '') {
		campoNomeAutor.value = '';
		campoNomeAutor.style.backgroundColor = '';
		campoNomeAutor.style.borderColor = '';
		return;
	}
	
	// Fazer requisição AJAX para buscar o autor
	fetch(`buscar_autor.php?id_autor=${idAutor}`)
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				campoNomeAutor.value = data.nome_autor;
				campoNomeAutor.style.backgroundColor = '#d4edda';
				campoNomeAutor.style.borderColor = '#28a745';
			} else {
				campoNomeAutor.value = 'Autor não encontrado';
				campoNomeAutor.style.backgroundColor = '#f8d7da';
				campoNomeAutor.style.borderColor = '#dc3545';
			}
		})
		.catch(error => {
			console.error('Erro na busca:', error);
			campoNomeAutor.value = 'Erro na consulta';
			campoNomeAutor.style.backgroundColor = '#f8d7da';
			campoNomeAutor.style.borderColor = '#dc3545';
		});
}

// Função para buscar editora pelo ID
function buscarEditora() {
	const idEditora = document.getElementById('cod_editora').value;
	const campoNomeEditora = document.getElementById('nome_editora');
	
	if (idEditora.trim() === '') {
		campoNomeEditora.value = '';
		campoNomeEditora.style.backgroundColor = '';
		campoNomeEditora.style.borderColor = '';
		return;
	}
	
	// Fazer requisição AJAX para buscar a editora
	fetch(`buscar_editora.php?id_editora=${idEditora}`)
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				campoNomeEditora.value = data.nome_editora;
				campoNomeEditora.style.backgroundColor = '#d4edda';
				campoNomeEditora.style.borderColor = '#28a745';
			} else {
				campoNomeEditora.value = 'Editora não encontrada';
				campoNomeEditora.style.backgroundColor = '#f8d7da';
				campoNomeEditora.style.borderColor = '#dc3545';
			}
		})
		.catch(error => {
			console.error('Erro na busca:', error);
			campoNomeEditora.value = 'Erro na consulta';
			campoNomeEditora.style.backgroundColor = '#f8d7da';
			campoNomeEditora.style.borderColor = '#dc3545';
		});
}

// Função para buscar doador pelo ID
function buscarDoador() {
	const idDoador = document.getElementById('cod_doador').value;
	const campoNomeDoador = document.getElementById('nome_doador');
	
	if (idDoador.trim() === '') {
		campoNomeDoador.value = '';
		campoNomeDoador.style.backgroundColor = '';
		campoNomeDoador.style.borderColor = '';
		return;
	}
	
	// Fazer requisição AJAX para buscar o doador
	fetch(`buscar_doador.php?id_doador=${idDoador}`)
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				campoNomeDoador.value = data.nome_doador;
				campoNomeDoador.style.backgroundColor = '#d4edda';
				campoNomeDoador.style.borderColor = '#28a745';
			} else {
				campoNomeDoador.value = 'Doador não encontrado';
				campoNomeDoador.style.backgroundColor = '#f8d7da';
				campoNomeDoador.style.borderColor = '#dc3545';
			}
		})
		.catch(error => {
			console.error('Erro na busca:', error);
			campoNomeDoador.value = 'Erro na consulta';
			campoNomeDoador.style.backgroundColor = '#f8d7da';
			campoNomeDoador.style.borderColor = '#dc3545';
		});
}

// Função para buscar livro pelo ID
function buscarLivro() {
	const idLivro = document.getElementById('cod_livro').value;
	const campoTitulo = document.getElementById('titulo');
	
	if (idLivro.trim() === '') {
		campoTitulo.value = '';
		campoTitulo.style.backgroundColor = '';
		campoTitulo.style.borderColor = '';
		return;
	}
	
	// Fazer requisição AJAX para buscar o livro
	fetch(`buscar_livro.php?id_livro=${idLivro}`)
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				campoTitulo.value = data.titulo;
				campoTitulo.style.backgroundColor = '#d4edda';
				campoTitulo.style.borderColor = '#28a745';
			} else {
				campoTitulo.value = 'Livro não encontrado';
				campoTitulo.style.backgroundColor = '#f8d7da';
				campoTitulo.style.borderColor = '#dc3545';
			}
		})
		.catch(error => {
			console.error('Erro na busca:', error);
			campoTitulo.value = 'Erro na consulta';
			campoTitulo.style.backgroundColor = '#f8d7da';
			campoTitulo.style.borderColor = '#dc3545';
		});
}

// Função para buscar cliente pelo ID
function buscarCliente() {
	const idCliente = document.getElementById('cod_cliente').value;
	const campoNome = document.getElementById('nome');
	
	if (idCliente.trim() === '') {
		campoNome.value = '';
		campoNome.style.backgroundColor = '';
		campoNome.style.borderColor = '';
		return;
	}
	
	// Fazer requisição AJAX para buscar o cliente
	fetch(`buscar_cliente.php?id_cliente=${idCliente}`)
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				campoNome.value = data.nome;
				campoNome.style.backgroundColor = '#d4edda';
				campoNome.style.borderColor = '#28a745';
			} else {
				campoNome.value = 'Cliente não encontrado';
				campoNome.style.backgroundColor = '#f8d7da';
				campoNome.style.borderColor = '#dc3545';
			}
		})
		.catch(error => {
			console.error('Erro na busca:', error);
			campoNome.value = 'Erro na consulta';
			campoNome.style.backgroundColor = '#f8d7da';
			campoNome.style.borderColor = '#dc3545';
		});
}

// Utilitário de debounce para evitar muitas requisições enquanto digita
function debounce(func, delay) {
	let timeoutId;
	return function() {
		const context = this;
		const args = arguments;
		clearTimeout(timeoutId);
		timeoutId = setTimeout(function() {
			func.apply(context, args);
		}, delay);
	};
}

// Versões com debounce das buscas
const buscarAutorDebounced = debounce(buscarAutor, 300);
const buscarEditoraDebounced = debounce(buscarEditora, 300);
const buscarDoadorDebounced = debounce(buscarDoador, 300);
const buscarLivroDebounced = debounce(buscarLivro, 300);
const buscarClienteDebounced = debounce(buscarCliente, 300);

// Sincronização do Gênero: select <-> campo ID
function syncGeneroFromSelect() {
	const select = document.getElementById('cod_genero');
	const campoId = document.getElementById('cod_genero_id');
	if (!select || !campoId) return;
	campoId.value = select.value || '';
	campoId.style.backgroundColor = select.value ? '#d4edda' : '';
	campoId.style.borderColor = select.value ? '#28a745' : '';
}

function syncGeneroFromId() {
	const select = document.getElementById('cod_genero');
	const campoId = document.getElementById('cod_genero_id');
	if (!select || !campoId) return;
	const alvo = (campoId.value || '').trim();
	let found = false;
	for (let i = 0; i < select.options.length; i++) {
		if (select.options[i].value === alvo) {
			select.selectedIndex = i;
			found = true;
			break;
		}
	}
	if (!found) {
		select.selectedIndex = 0; // volta para placeholder
	}
	// feedback visual opcional no select
	select.style.backgroundColor = found ? '#d4edda' : '';
	select.style.borderColor = found ? '#28a745' : '';
}

const syncGeneroFromIdDebounced = debounce(syncGeneroFromId, 200);

// Adicionar eventos quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
	const campoIdAutor = document.getElementById('cod_autor');
	const campoIdEditora = document.getElementById('cod_editora');
	const campoIdDoador = document.getElementById('cod_doador');
	const campoIdLivro = document.getElementById('cod_livro');
	const campoIdCliente = document.getElementById('cod_cliente');
	const selectGenero = document.getElementById('cod_genero');
	const campoGeneroId = document.getElementById('cod_genero_id');
	
	// Buscar automaticamente enquanto digita (com debounce)
	if (campoIdAutor) {
		campoIdAutor.addEventListener('input', buscarAutorDebounced);
	}
	if (campoIdEditora) {
		campoIdEditora.addEventListener('input', buscarEditoraDebounced);
	}
	if (campoIdDoador) {
		campoIdDoador.addEventListener('input', buscarDoadorDebounced);
	}
	if (campoIdLivro) {
		campoIdLivro.addEventListener('input', buscarLivroDebounced);
	}
	if (campoIdCliente) {
		campoIdCliente.addEventListener('input', buscarClienteDebounced);
	}
	
	// Ainda buscar ao sair do campo (fallback)
	if (campoIdAutor) campoIdAutor.addEventListener('blur', buscarAutor);
	if (campoIdEditora) campoIdEditora.addEventListener('blur', buscarEditora);
	if (campoIdDoador) campoIdDoador.addEventListener('blur', buscarDoador);
	if (campoIdLivro) campoIdLivro.addEventListener('blur', buscarLivro);
	if (campoIdCliente) campoIdCliente.addEventListener('blur', buscarCliente);
	
	// Gênero: sincronizar nos dois sentidos
	if (selectGenero) selectGenero.addEventListener('change', syncGeneroFromSelect);
	if (campoGeneroId) campoGeneroId.addEventListener('input', syncGeneroFromIdDebounced);
	// Sincroniza estado inicial, se houver valores pré-preenchidos
	syncGeneroFromSelect();
});
