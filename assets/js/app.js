String.prototype.replaceAll = function(search, replace) {
	return this.split(search).join(replace);
}

String.prototype.contains = function(search) {
	return this.indexOf(search) > -1
}

Object.getLast = (object, param) => {

	let last = Object.keys(object).slice(-1)[0];

	return param ? object[last] : object[last][param];
}

$.ajaxSetup({
	type: 'POST',
	beforeSend: () => {
		$(".loading").fadeIn(200);
	},
	complete: xhr => {
		$(".loading").fadeOut(200);
	}
});


const escapeHtml = html => {
	return html.split("&").join("&amp;")
		.split("<").join("&lt;")
		.split(">").join("&gt;")
		.split('"').join("&quot;")
		.split("'").join("&#039;");
}

/**
 * Remueve los espacios en blanco a la derecha e izquierda
 * @param string text
 */
const trim = (text) => text.replace(/^\s+|\s+$/g, "");

/**
 * Remueve los espacios en blanco a la izquierda
 * @param string text
 * return string
 */
const ltrim = (text, character) => {

	if (!character) {
		return text.replace(/^\s+/, "");
	}

	let result = "",
		splited = text.split("");

	if (splited[0] != character) {
		return text;
	}

	let charFinded = false;

	for (let idx = 0; idx < splited.length; idx++) {
		if (splited[idx] != character) {
			charFinded = true;
			continue;
		}

		if (charFinded) {
			break;
		}

		delete splited[idx];
	}

	return splited.join("");
};

/**
 * Remueve los espacios en blanco a la derecha
 * @param string text
 */
const rtrim = (text, character) => {

	if (!character) {
		return text.replace(/\s+$/, "");
	}

	let result = "",
		splited = text.split("").reverse();

	let charFinded = false;

	for (let idx = 0; idx < splited.length; idx++) {
		if (splited[idx] != character) {
			charFinded = true;
			continue;
		}

		if (charFinded) {
			break;
		}

		delete splited[idx];
	}

	return splited.reverse().join("");
};

/**
 * Detectamos si un string esta vacio
 * @param string val
 */
const isEmpty = val => {

	switch (typeof val) {

		case "object":

			var clean = false;

			for (var x in val) {

				if (!isEmpty(val[x])) {
					clean = true;
					break;
				}
			}

			return !clean;

			break;

		default:
			return (val == null || val.toString().replace(/^\s+|\s+$/gm, "").length === 0);
	}

}

const blur = () => {
	if (document.activeElement) {
		document.activeElement.blur();
	}
}

const domain = `${location.origin}/pinv`;

const cargarCategorias = () => {

	let div = $("div[name='categ-buscar']");
	let ajax = {
		url: `${domain}/api/categorias/get_all`,
		success: rsp => {

			try {
				rsp = JSON.parse(rsp);
			} catch (e) {
				console.error(e);
				return;
			}

			if (rsp.code != 200) {
				return;
			}

			let show = div.css("display") != "none"; 

			div.hide("fade", 250, () => {

				div.empty();

				rsp.data.forEach(categoria => {

					let html = `
					<div class="card">
						<form class="modify-categ">
							<span>
								Nombre
							</span>
							<input type="text" name="nombre" value="${escapeHtml(categoria.nombre)}" data-id="${new Number(categoria.id)}">
							<input type="button" class="eliminar" value="Eliminar">
							<input type="submit" value="Modificar">
						</form>
					</div>
					`;

					div.append(html);
				});

				if (show) 
					div.show("blind", 500);
			});			
		}
	}

	$.ajax(ajax);
}

const cargarMarcas = () => {

	let div = $("div[name='marca-buscar']");
	let ajax = {
		url: `${domain}/api/marcas/get_all`,
		success: rsp => {

			try {
				rsp = JSON.parse(rsp);
			} catch (e) {
				console.error(e);
				return;
			}

			if (rsp.code != 200) {
				return;
			}

			let show = div.css("display") != "none"; 

			div.hide("fade", 250, () => {

				div.empty();

				rsp.data.forEach(marca => {

					let html = `
					<div class="card">
						<form class="modify-marca">
							<span>
								Nombre
							</span>
							<input type="text" name="nombre" value="${escapeHtml(marca.nombre)}" data-id="${new Number(marca.id)}">
							<input type="button" class="eliminar" value="Eliminar">
							<input type="submit" value="Modificar">
						</form>
					</div>
					`;

					div.append(html);
				});

				if (show) 
					div.show("blind", 500);
			});			
		}
	}

	$.ajax(ajax);	
}

const cargarEstatusObj = () => {

	let div = $("div[name='estatus-buscar']");
	let ajax = {
		url: `${domain}/api/estatus_obj/get_all`,
		success: rsp => {

			try {
				rsp = JSON.parse(rsp);
			} catch (e) {
				console.error(e);
				return;
			}

			if (rsp.code != 200) {
				return;
			}

			let show = div.css("display") != "none"; 

			div.hide("fade", 250, () => {

				div.empty();

				rsp.data.forEach(estatus => {

					let html = `
					<div class="card">
						<form class="modify-estatus">
							<span>
								Nombre
							</span>
							<input type="text" name="nombre" value="${escapeHtml(estatus.nombre)}" data-id="${new Number(estatus.id)}">
							<input type="button" class="eliminar" value="Eliminar">
							<input type="submit" value="Modificar">
						</form>
					</div>
					`;

					div.append(html);
				});

				if (show) 
					div.show("blind", 500);
			});			
		}
	}

	$.ajax(ajax);	
}

const cargarDepartamentos = () => {

	let div = $("div[name='departamento-buscar']");
	let ajax = {
		url: `${domain}/api/departamentos/get_all`,
		success: rsp => {

			try {
				rsp = JSON.parse(rsp);
			} catch (e) {
				console.error(e);
				return;
			}

			if (rsp.code != 200) {
				return;
			}

			let show = div.css("display") != "none"; 

			div.hide("fade", 250, () => {

				div.empty();

				rsp.data.forEach(departamento => {

					let html = `
					<div class="card">
						<form class="modify-departamento">
							<span>
								Nombre
							</span>
							<input type="text" name="nombre" value="${escapeHtml(departamento.nombre)}" data-id="${new Number(departamento.id)}">
							<input type="button" class="eliminar" value="Eliminar">
							<input type="submit" value="Modificar">
						</form>
					</div>
					`;

					div.append(html);
				});

				if (show) 
					div.show("blind", 500);
			});			
		}
	}

	$.ajax(ajax);
}

$(document).on("click", ".burger", function () {
	let ul = $(this).prev("ul");
	ul.toggleClass("active");
});


$(document).on("click", "form span", function () {
	let self = $(this);
	self.next("input").focus();
});
$(document).on("submit", "form.login", function (e) {
	e.preventDefault();
	blur();

	let self = $(this);
	let inputs = self.find("input:not([type='submit'])");
	let nick = inputs[0].value;
	let password = inputs[1].value;
});

$(document).on("submit", ".login", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(self[0]);

	let data = {
		nick: form.get('nick'),
		password: form.get('password')
	};

	let check = {
		stop: false,
		msg: ""
	};

	for(let item in data) {

		data[item] = trim(data[item]);
		self.find(`input[name='${item}']`).val(data[item]);

		if (isEmpty(data[item])) {
			check = {
				stop: true,
				msg: `Alto: Campo ${item} vacio`
			};
			break;
		}
	}

	if (check.stop) {
		showDialog({
            title: 'Fallo',
            text: check.msg,
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	$.ajax({
		url: `${domain}/api/user/login`,
		data: data,
		success: rsp => {

			try {
				rsp = JSON.parse(rsp);
			} catch (e) {
				showDialog({
					title: "Error recibiendo datos",
					text: rsp,
					positive: {
						title: "Ok"
					}
				});
				return console.error(e);
			}

			if(rsp.code != 200) {
				showDialog({
					title: "Error recibiendo datos",
					text: rsp.data,
					positive: {
						title: "Ok"
					}
				});
				return;
			}

			if(location.href == domain) {
				location.reload();
			} else {
				location.href = domain;	
			}			
		}
	});
});

$(document).on("click", "form.create-user table tr td span, form.modificar-user table tr td span", function () {
	let self = $(this);
	let parent = self.parents("tr:first");
	let input = parent.find("td:last").find("input");
	input.prop("checked", !input.is(":checked"));
});

$(document).on("submit", "form.create-user", function (ev) {
	ev.preventDefault();

	let self = $(this);
	let form = new FormData(self[0]);
	let data = {
		nick: form.get('nick'),
		cedula: form.get('cedula'),
		access: form.getAll('access[]')
	};

	data.nick = trim(data.nick);
	data.cedula = new Number(data.cedula);

	if (isEmpty(data.nick)) {
		showDialog({
            title: 'Fallo',
            text: 'El nick no puede estar vacio',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}


	if (isNaN(data.cedula) || data.cedula < 999999) {
		showDialog({
            title: 'Fallo',
            text: 'La cedula es incorrecta',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (data.access.length < 1) {
		showDialog({
            title: 'Fallo',
            text: 'Debe indicar al menos un acceso',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
	    title: '¿Desea agregar a este usuario?',
	    text: "",
	    negative: {
	        title: 'No'
	    },
	    positive: {
	        title: 'Agregar',
	        onClick: () => {
				$.ajax({
					url: `${domain}/api/user/crear`,
					data: data,
					success: rsp => {
						
						try {
							rsp = JSON.parse(rsp);
						} catch (e) {
							showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							console.error(e);
							return;
						}

						if (rsp.code == 500) {
							showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							return;
						}

						showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

				        self.find("input:not([type='submit'], [type='checkbox'])").val("");
				        self.find("input[type='checkbox']").prop("checked", false);
					}
				});
	        }
	    }
	});

});

$(document).on("submit", "form.create-person", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(self[0]);
	let data = {
		nombre: form.get('nombre'),
		apellido: form.get('apellido'),
		cedula: form.get('cedula')
	};

	data.nombre = trim(data.nombre);
	data.apellido = trim(data.apellido);

	if (isEmpty(data.nombre) || isEmpty(data.apellido)) {
		showDialog({
            title: 'Fallo',
            text: 'El nombre y apellido no pueden estar vacios',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	data.cedula = new Number(data.cedula);

	if (isNaN(data.cedula) || data.cedula < 999999) {
		showDialog({
            title: 'Fallo',
            text: 'La cedula es incorrecta',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
	    title: '¿Desea agregar a esta persona?',
	    text: "",
	    negative: {
	        title: 'No'
	    },
	    positive: {
	        title: 'Agregar',
	        onClick: () => {
				$.ajax({
					url: `${domain}/api/persona/crear`,
					data: data,
					success: rsp => {
						
						try {
							rsp = JSON.parse(rsp);
						} catch (e) {
							showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							console.error(e);
							return;
						}

						if (rsp.code == 500) {
							showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							return;
						}

						showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

				        self.find("input:not([type='submit'])").val("");
					}
				});
	        }
	    }
	});
	
});

$(document).on("submit", "form.modificar-user", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(self[0]);
	let data = {
		id: self.find("input[name='nick']").data("id"),
		access: form.getAll('access[]'),
		estatus: form.get('estatus')
	};

	if (isEmpty(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id no puede estar vacio',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (data.estatus < 1 || isNaN(data.estatus)) {
		showDialog({
            title: 'Fallo',
            text: 'Debe indicar un estatus valido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (data.access.length < 1) {
		showDialog({
            title: 'Fallo',
            text: 'Debe indicar al menos un acceso',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
	    title: '¿Desea modificar a este usuario?',
	    text: "",
	    negative: {
	        title: 'No'
	    },
	    positive: {
	        title: 'Modificar',
	        onClick: () => {
				$.ajax({
					url: `${domain}/api/user/modificar`,
					data: data,
					success: rsp => {
						
						try {
							rsp = JSON.parse(rsp);
						} catch (e) {
							showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							console.error(e);
							return;
						}

						if (rsp.code == 500) {
							showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							return;
						}

						showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });
					}
				});
	        }
	    }
	});
});

$(document).on("submit", "form.search-user", function (ev) {

	ev.preventDefault();
	let self = $(this);
	let form = new FormData(this);
	let nick = trim(form.get('nick'));

	if (isEmpty(nick)) {
		return;
	}

	let url = `${domain}/user/modificar/${encodeURIComponent(nick)}`;

	if (window.location.href === url)
		return;

	window.location.href = url;
});

$(document).on("submit", "form.search-person", function (ev) {

	ev.preventDefault();
	let self = $(this);
	let form = new FormData(this);
	let cedula = trim(form.get('cedula'));

	if (isEmpty(cedula)) {
		return;
	}

	let url = `${domain}/persona/modificar/${encodeURIComponent(cedula)}`;

	if (window.location.href === url)
		return;

	window.location.href = url;
});

$(document).on("submit", "form.update-password", function (ev) {

	ev.preventDefault();
	let self = $(this);
	let form = new FormData(this);
	let data = {
		nueva: trim(form.get("new")),
		confirmar: trim(form.get("confirm")),
	};

	let check = {
		stop: false,
		error: ""
	};

	for(let field in data) {

		if (isEmpty(data[field])) {
			check.stop = true;
			check.error = `Campo Contraseña ${field} vacio`;
			break;
		}
	}

	if (check.stop) {
		showDialog({
            title: 'Fallo',
            text: check.error,
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (data.vieja == data.nueva) {
		showDialog({
            title: 'Fallo',
            text: "La contraseña vieja no pueden ser igual a la contraseña nueva",
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (data.nueva != data.confirmar) {
		showDialog({
            title: 'Fallo',
            text: "La contraseña nueva no concuerda con la confirmacion",
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	delete data.confirmar;
	delete data.vieja;

	showDialog({
	    title: '¿Desea cambiar su contraseña?',
	    text: "",
	    negative: {
	        title: 'No'
	    },
	    positive: {
	        title: 'Si',
	        onClick: () => {
				$.ajax({
					url: `${domain}/api/user/update_my_password`,
					data: data,
					success: rsp => {
						
						try {
							rsp = JSON.parse(rsp);
						} catch (e) {
							showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							console.error(e);
							return;
						}

						if (rsp.code == 500) {
							showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							return;
						}

						self.find("input[type='password']").val("");

						showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });
					}
				});
	        }
	    }
	});
});

$(document).on("click", "input.reset-password", function () {

	let self = $(this);
	let form = self.parents("form:first");
	let data = {
		id: form.find("input[name='nick']").data("id")
	};

	if (isEmpty(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id no puede estar vacio',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
	    title: '¿Desea reiniciar la contraseña de este usuario?',
	    text: "",
	    negative: {
	        title: 'No'
	    },
	    positive: {
	        title: 'Si',
	        onClick: () => {
				$.ajax({
					url: `${domain}/api/user/reset_password`,
					data: data,
					success: rsp => {
						
						try {
							rsp = JSON.parse(rsp);
						} catch (e) {
							showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							console.error(e);
							return;
						}

						if (rsp.code == 500) {
							showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							return;
						}

						showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });
					}
				});
	        }
	    }
	});
});

$(document).on("submit", "form.modify-person", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(self[0]);
	let data = {
		nombre: form.get('nombre'),
		apellido: form.get('apellido'),
		cedula: form.get('cedula'),
		pid: trim(form.get('pid'))
	};

	data.nombre = trim(data.nombre);
	data.apellido = trim(data.apellido);

	if (isEmpty(data.nombre) || isEmpty(data.apellido)) {
		showDialog({
            title: 'Fallo',
            text: 'El nombre y apellido no pueden estar vacios',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (isEmpty(data.pid)) {
		showDialog({
            title: 'Fallo',
            text: 'El id no puede estar vacio',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	data.cedula = new Number(data.cedula);

	if (isNaN(data.cedula) || data.cedula < 999999) {
		showDialog({
            title: 'Fallo',
            text: 'La cedula es incorrecta',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
	    title: '¿Desea modificar a esta persona?',
	    text: "",
	    negative: {
	        title: 'No'
	    },
	    positive: {
	        title: 'Modificar',
	        onClick: () => {
				$.ajax({
					url: `${domain}/api/persona/modificar`,
					data: data,
					success: rsp => {
						
						try {
							rsp = JSON.parse(rsp);
						} catch (e) {
							showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							console.error(e);
							return;
						}

						if (rsp.code == 500) {
							showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
							return;
						}

						showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar',
				                onClick: () => {
				                	let url = window.location.href.split("/");

				                	if(url[url.length -1] != data.cedula) {
				                		window.location.href = `${domain}/persona/modificar/${data.cedula}`;
				                	}
				                }
				            }
				        });
					}
				});
	        }
	    }
	});
});

$(document).on("click", "div.categs input[type='radio'][name='tab']", function () {
	let self = $(this);
	let parent = self.parents("div:first");
	parent.find(`div.box:not([name='categ-${self.val()}'])`).hide("fade", 250);
	parent.find(`div.box[name='categ-${self.val()}']`).show("blind", 500);	
});

$(document).on("click", "div.categs input[type='radio'][value='buscar']", cargarCategorias);

$(document).on("submit", "form.create-categ", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre")
	}

	data.nombre = trim(data.nombre);

	if (isEmpty(data.nombre)) {
		showDialog({
            title: 'Fallo',
            text: 'El nombre de la categoria no puede estar vacio',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea agregar esta categoria?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Agregar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/categorias/agregar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				self.find("input[type='text']").val("");
        				cargarCategorias();
        			}
        		});
        	}
        }
    });
});

$(document).on("submit", "form.modify-categ", function (ev) {
	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre"),
		id: self.find("input[type='text']").data("id")
	}

	if (isNaN(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id de la categoria es invalido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea modificar esta categoria?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Modificar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/categorias/modificar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				cargarCategorias();
        			}
        		});
        	}
        }
    });
});

$(document).on("click", "form.modify-categ input.eliminar", function () {

	let parent = $(this).parent();
	let data = {
		id: parent.find("input[type='text']").data("id")
	};

	if (isNaN(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id de la categoria es invalido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea eliminar esta categoria?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Eliminar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/categorias/eliminar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				cargarCategorias();
        			}
        		});
        	}
        }
    });
});

$(document).on("click", "div.marcas input[type='radio'][name='tab']", function () {
	let self = $(this);
	let parent = self.parents("div:first");
	parent.find(`div.box:not([name='marca-${self.val()}'])`).hide("fade", 250);
	parent.find(`div.box[name='marca-${self.val()}']`).show("blind", 500);	
});

$(document).on("click", "div.marcas input[type='radio'][value='buscar']", cargarMarcas);

$(document).on("submit", "form.crear-marca", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre")
	}

	data.nombre = trim(data.nombre);

	if (isEmpty(data.nombre)) {
		showDialog({
            title: 'Fallo',
            text: 'El nombre de la marca no puede estar vacio',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea agregar esta marca?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Agregar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/marcas/agregar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				self.find("input[type='text']").val("");
        				cargarMarcas();
        			}
        		});
        	}
        }
    });
});

$(document).on("submit", "form.modify-marca", function (ev) {
	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre"),
		id: self.find("input[type='text']").data("id")
	}

	if (isNaN(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id de la marca es invalido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea modificar esta marca?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Modificar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/marcas/modificar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				cargarMarcas();
        			}
        		});
        	}
        }
    });
});

$(document).on("click", "form.modify-marca input.eliminar", function () {

	let parent = $(this).parent();
	let data = {
		id: parent.find("input[type='text']").data("id")
	};

	if (isNaN(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id de la marca es invalido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea eliminar esta marca?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Eliminar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/marcas/eliminar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				cargarMarcas();
        			}
        		});
        	}
        }
    });
});
/**********/

$(document).on("click", "div.estatus_obj input[type='radio'][name='tab']", function () {
	let self = $(this);
	let parent = self.parents("div:first");
	parent.find(`div.box:not([name='estatus-${self.val()}'])`).hide("fade", 250);
	parent.find(`div.box[name='estatus-${self.val()}']`).show("blind", 500);	
});

$(document).on("click", "div.estatus_obj input[type='radio'][value='buscar']", cargarEstatusObj);

$(document).on("submit", "form.crear-estatus", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre")
	}

	data.nombre = trim(data.nombre);

	if (isEmpty(data.nombre)) {
		showDialog({
            title: 'Fallo',
            text: 'El nombre del departamento no puede estar vacio',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea agregar este estatus?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Agregar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/estatus_obj/agregar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				self.find("input[type='text']").val("");
        				cargarEstatusObj();
        			}
        		});
        	}
        }
    });
});

$(document).on("submit", "form.modify-estatus", function (ev) {
	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre"),
		id: self.find("input[type='text']").data("id")
	}

	if (isNaN(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id del estatus es invalido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea modificar este estatus?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Modificar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/estatus_obj/modificar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				cargarEstatusObj();
        			}
        		});
        	}
        }
    });
});

$(document).on("click", "form.modify-estatus input.eliminar", function () {

	let parent = $(this).parent();
	let data = {
		id: parent.find("input[type='text']").data("id")
	};

	if (isNaN(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id del estatus es invalido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea eliminar este estatus?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Eliminar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/estatus_obj/eliminar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				cargarEstatusObj();
        			}
        		});
        	}
        }
    });
});
/**********/

$(document).on("click", "div.departamentos input[type='radio'][name='tab']", function () {
	let self = $(this);
	let parent = self.parents("div:first");
	parent.find(`div.box:not([name='departamento-${self.val()}'])`).hide("fade", 250);
	parent.find(`div.box[name='departamento-${self.val()}']`).show("blind", 500);	
});

$(document).on("click", "div.departamentos input[type='radio'][value='buscar']", cargarDepartamentos);

$(document).on("submit", "form.crear-departamento", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre")
	}

	data.nombre = trim(data.nombre);

	if (isEmpty(data.nombre)) {
		showDialog({
            title: 'Fallo',
            text: 'El nombre del departamento no puede estar vacio',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea agregar este departamento?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Agregar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/departamentos/agregar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				self.find("input[type='text']").val("");
        				cargarDepartamentos();
        			}
        		});
        	}
        }
    });
});

$(document).on("submit", "form.modify-departamento", function (ev) {
	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre"),
		id: self.find("input[type='text']").data("id")
	}

	if (isNaN(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id del departamento es invalido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea modificar este departamento?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Modificar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/departamentos/modificar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				cargarDepartamentos();
        			}
        		});
        	}
        }
    });
});

$(document).on("click", "form.modify-departamento input.eliminar", function () {

	let parent = $(this).parent();
	let data = {
		id: parent.find("input[type='text']").data("id")
	};

	if (isNaN(data.id)) {
		showDialog({
            title: 'Fallo',
            text: 'El id del departamento es invalido',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	showDialog({
        title: '',
        text: '¿Desea eliminar este departamento?',
        negative: {
            title: 'Cerrar'
        },
        positive: {
        	title: "Eliminar",
        	onClick: () => {

        		$.ajax({
        			url: `${domain}/api/departamentos/eliminar`,
        			data: data,
        			success: rsp => {

        				try { 
        					rsp = JSON.parse(rsp);
        				} catch (e) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp,
					            negative: {
					                title: 'Cerrar'
					            }
					        });

					        console.error(e);
        					return;
        				}

        				if (rsp.code != 200) {
        					showDialog({
					            title: 'Fallo',
					            text: rsp.data,
					            negative: {
					                title: 'Cerrar'
					            }
					        });
        					return;
        				}

        				showDialog({
				            title: 'Exito',
				            text: rsp.data,
				            negative: {
				                title: 'Cerrar'
				            }
				        });

        				cargarDepartamentos();
        			}
        		});
        	}
        }
    });
});

$(document).on("submit", "form.inventario-agregar", function (ev) {

	ev.preventDefault();
	let self = $(this);
	let form = new FormData(this);
	let data = {
		nombre: form.get("nombre"),
		categoria: form.get("categoria"),
		marca: form.get("marca"),
		serial: form.get("serial"),
		estado: form.get("estado"),
		descripcion: form.get("descripcion")
	};

	for (let field in data) {
		data[field] = trim(data[field]);
	}

	const guardarDatos = () => {

		if (isEmpty(data.serial)) {
			showDialog({
	            title: 'Fallo',
	            text: 'El serial del objeto no puede estar vacio',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		if (isEmpty(data.descripcion)) {
			showDialog({
	            title: 'Fallo',
	            text: 'La descripcion del objeto no puede estar vacia',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}
		
		if (isNaN(data.estado))	{
			showDialog({
	            title: 'Fallo',
	            text: 'El estado del objeto no puede estar vacio',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		if (isNaN(data.categoria))	{
			showDialog({
	            title: 'Fallo',
	            text: 'La categoria del objeto no puede estar vacia',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		if (isNaN(data.marca))	{
			showDialog({
	            title: 'Fallo',
	            text: 'La marca del objeto no puede estar vacia',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		let ajax = {
			url: `${domain}/api/inventario/agregar`,
			data: data,
			success: rsp => {

				try {
					rsp = JSON.parse(rsp);
				} catch (e) {
					showDialog({
			            title: "Error",
			            text: rsp,
			            negative: {
			                title: 'Cerrar'
			            }
			        });
					console.error(e);
					return;
				}

				if (rsp.code != 200) {
					showDialog({
			            title: 'Fallo',
			            text: rsp.data,
			            negative: {
			                title: 'Cerrar'
			            }
			        });
					return;
				}

				showDialog({
		            title: 'Exito',
		            text: `${rsp.data}
		            <br>
		            <div class="card inv">
		            	<a href="${domain}/inventario/modificar/${encodeURIComponent(rsp.id)}">Modificar</a>
		            	<a href="${domain}/inventario/asignar/${encodeURIComponent(rsp.id)}">Asignar</a>
		            </div>
		            `,
		            negative: {
		                title: 'Cerrar'
		            }
		        });

		        self.find("input[type='text']").val("");
			}
		}

		showDialog({
	        title: '',
	        text: '¿Desea agregar este objeto?',
	        negative: {
	            title: 'Cerrar'
	        },
	        positive: {
	        	title: "Agregar",
	        	onClick: () => {
	        		$.ajax(ajax);
	        	}
	        }
	    });

	}

	if (isEmpty(data.nombre)) {
		showDialog({
            title: '',
            text: '¿Esta seguro que quiere guardar sin nombrar el objeto?',
            negative: {
                title: 'No'
            },
            positive: {
            	title: 'Si',
            	onClick: guardarDatos
            }
        });
		return;
	} else {
		guardarDatos();
	}
	
});

$(document).on("change", "select.art-assign-type", function () {

	let self = $(this);
	let persona = self.nextAll("div.persona:first");
	let puesto = self.nextAll("div.puesto:first");

	switch (this.value) {
		case "persona":
			persona.show("blind", 500, () => {
				puesto.hide("blind", 500);
			});
		break;
		case "puesto":
			puesto.show("blind", 500, () => {
				persona.hide("blind", 500);
			});
		break;
		case "nadie":
			puesto.hide("blind", 500, () => {
				persona.hide("blind", 500);
			});
		break;
		default:
			puesto.show("blind", 500, () => {
				persona.show("blind", 500);
			});
		break;
	}
});

$(document).on("submit", "form.search-articulos", function(ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);

	let pid = form.get("pid");
	let url = `${domain}/inventario/${self.data("type")}/${pid}`;

	if (window.location.href === url)
		return;

	window.location.href = url;
});

$(document).on("submit", "form.inventario-asignar", function(ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		departamento: form.get("departamento"),
		asignar: form.get("asignar"),
		persona: form.get("persona"),
		puesto: form.get("puesto"),
		estatus: form.get("estatus"),
		accion: form.get("accion"),
		pid: self.data("pid"),
	}

	for(let field in data) {
		data[field] = trim(data[field]);
	}

	data.departamento = new Number(data.departamento);

	if (isEmpty(data.pid)) {
		showDialog({
            title: 'Error',
            text: 'No se encontro el articulo a asignar',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (isNaN(data.departamento)) {
		showDialog({
            title: 'Error',
            text: 'No se encontro el departamento a asignar',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (isNaN(data.estatus)) {
		showDialog({
            title: 'Error',
            text: 'No se encontro el estatus del objeto',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (data.asignar == "persona" && isEmpty(data.persona)) {
		showDialog({
            title: 'Error',
            text: 'Debe indicar la cedula a quien se asignara el articulo',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (data.asignar == "puesto" && isEmpty(data.puesto)) {
		showDialog({
            title: 'Error',
            text: 'Debe indicar el puesto donde se asignara el articulo',
            negative: {
                title: 'Cerrar'
            }
        });
		return;
	}

	if (data.asignar == "nadie") {
		data.puesto = null;
		data.persona = null;
	}

	let ajax = {
		url: `${domain}/api/inventario/asignar`,
		data: data,
		success: rsp => {

			try {
				rsp = JSON.parse(rsp);
			} catch (e) {

				showDialog({
		            title: 'Error',
		            text: rsp,
		            negative: {
		                title: 'Cerrar'
		            }
		        });

				console.error(e);
				return;
			}

			if (rsp.code != 200) {
				showDialog({
		            title: 'Error',
		            text: rsp.data,
		            negative: {
		                title: 'Cerrar'
		            }
		        });
				return;
			}

			showDialog({
	            title: 'Exito',
	            text: rsp.data,
	            negative: {
	                title: 'Cerrar'
	            }
	        });

	        self.find("input[type='text'], input[type='number']").val("");
		}
	}

	showDialog({
        title: '',
        text: '¿Desea realizar esta accion?',
        negative: {
            title: 'No'
        },
        positive: {
        	title: "Si",
        	onClick: () => {
        		$.ajax(ajax);
        	}
        }
    });
});

$(document).on("submit", "form.inventario-modificar", function (ev) {

	ev.preventDefault();
	let self = $(this);
	let form = new FormData(this);
	let data = {
		pid: self.data("pid"),
		nombre: form.get("nombre"),
		categoria: form.get("categoria"),
		marca: form.get("marca"),
		serial: form.get("serial"),
		estado: form.get("estado"),
		descripcion: form.get("descripcion")
	};

	for (let field in data) {
		data[field] = trim(data[field]);
	}

	const guardarDatos = () => {

		if (isEmpty(data.pid)) {
			showDialog({
	            title: 'Fallo',
	            text: 'No se encuentra el articulo a modificar',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		if (isEmpty(data.serial)) {
			showDialog({
	            title: 'Fallo',
	            text: 'El serial del objeto no puede estar vacio',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		if (isEmpty(data.descripcion)) {
			showDialog({
	            title: 'Fallo',
	            text: 'La descripcion del objeto no puede estar vacia',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}
		
		if (isNaN(data.estado))	{
			showDialog({
	            title: 'Fallo',
	            text: 'El estado del objeto no puede estar vacio',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		if (isNaN(data.categoria))	{
			showDialog({
	            title: 'Fallo',
	            text: 'La categoria del objeto no puede estar vacia',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		if (isNaN(data.marca))	{
			showDialog({
	            title: 'Fallo',
	            text: 'La marca del objeto no puede estar vacia',
	            negative: {
	                title: 'Cerrar'
	            }
	        });
			return;
		}

		let ajax = {
			url: `${domain}/api/inventario/modificar`,
			data: data,
			success: rsp => {

				try {
					rsp = JSON.parse(rsp);
				} catch (e) {
					showDialog({
			            title: "Error",
			            text: rsp,
			            negative: {
			                title: 'Cerrar'
			            }
			        });
					console.error(e);
					return;
				}

				if (rsp.code != 200) {
					showDialog({
			            title: 'Fallo',
			            text: rsp.data,
			            negative: {
			                title: 'Cerrar'
			            }
			        });
					return;
				}

				showDialog({
		            title: 'Exito',
		            text: `${rsp.data}
		            <br>
		            <div class="card inv">
		            	<a href="${domain}/inventario/asignar/${encodeURIComponent(rsp.id)}">Asignar</a>
		            </div>
		            `,
		            negative: {
		                title: 'Cerrar',
		                onClick: () => {
		                	location.reload();
		                }
		            }
		        });
			}
		}

		showDialog({
	        title: '',
	        text: '¿Desea modificar este objeto?',
	        negative: {
	            title: 'Cerrar'
	        },
	        positive: {
	        	title: "Agregar",
	        	onClick: () => {
	        		$.ajax(ajax);
	        	}
	        }
	    });

	}

	if (isEmpty(data.nombre)) {
		showDialog({
            title: '',
            text: '¿Esta seguro que quiere guardar sin nombrar el objeto?',
            negative: {
                title: 'No'
            },
            positive: {
            	title: 'Si',
            	onClick: guardarDatos
            }
        });
		return;
	} else {
		guardarDatos();
	}
});

$(document).on("click", "form.search-inv-list input[type='button']", function () {

	let self = $(this);
	let form = self.parents("form:first");

	form.find("select").each((idx, select) => {
		select.selectedIndex = 0;
	});

	form.find("input[type='text']").each((idx, input) => {
		input.value = "";
	});
});

$(document).on("submit", "form.search-inv-list", function (ev) {

	ev.preventDefault();

	let self = $(this);
	let form = new FormData(this);
	let data = {
		pid: form.get("pid"),
		serial: form.get("serial"),
		categoria: form.get("categoria"),
		marca: form.get("marca"),
		estado: form.get("estado")
	}

	for(let field in data) {
		data[field] = trim(data[field]);

		if(isEmpty(data[field])) {
			delete data[field];
		}
	}

	if (Object.keys(data) < 1) {
		$("div.card.list").fadeIn(200);
		return;
	}

	let selected = $("div.card.list").filter((idx, elm) => {

		let add = true;

		for(let field in data) {

			if (elm.dataset[field] != data[field]) {				
				add = false;
				break;
			}
		}

		return add;
	});

	if (selected.length < 1) {
		$("div.card.list").fadeOut(200);
		return;
	}

	let notSelected = $("div.card.list").not(selected);

	selected.fadeIn(200);
	notSelected.fadeOut(200);
});

$(document).ready(() => {

	for(let idx = 1; idx < 7; idx++) {
		new FooPicker({
			id: `datepicker${idx}`,
		    dateFormat: 'yyyy-MM-dd'
		});

	}
});

$(document).on("change", "select.select-report", function () {
	let div = $("div.reporte-data");
	let self = $(this);
	let selected = this.value;
	let options = ["serial", "general", "estadistica"];

	div.fadeOut(200, () => {
		div.empty();
		div.fadeIn(200);
	});

	$(options.map(option => {
		return `div.reporte-${option}`
	}).join(",")).fadeOut(200);

	if (isEmpty(selected) || options.indexOf(selected) < 0) {
		console.log("not there")
		return;
	}

	$(`div.reporte-${selected}`).fadeIn(200);
});

$(document).on("submit", "div.reporte-serial form, div.reporte-general form, div.reporte-estadistica form", (ev) => {
	ev.preventDefault();
});

$(document).on("submit", "div.reporte-serial form", function () {

	let self = $(this);
	let form = new FormData(this);
	let data = {
		serial: form.get("serial"),
		fecha_min: form.get('fecha-min'),
		fecha_max: form.get('fecha-max')
	}

	if (isEmpty(data.serial)) {
		showDialog({
            title: 'Error',
            text: 'Debe indicar un serial',
            negative: {
                title: 'No'
            }
        });
		return;
	}

	$.ajax({
		url: `${domain}/api/reportes/serial`,
		data: data,
		success: rsp => {

			try { 
				rsp = JSON.parse(rsp);
			} catch (e) {
				showDialog({
		            title: 'Fallo',
		            text: rsp,
		            negative: {
		                title: 'Cerrar'
		            }
		        });

		        console.error(e);
				return;
			}

			if (rsp.code != 200) {
				showDialog({
		            title: 'Fallo',
		            text: rsp.data,
		            negative: {
		                title: 'Cerrar'
		            }
		        });
				return;
			}

	        let div = $("div.reporte-data");

	        div.fadeOut(200, () => {
	        	div.empty();
	        	div.html(rsp.data);
	        	div.fadeIn(200);
	        });
		}
	});
});

$(document).on("submit", "div.reporte-general form, div.reporte-estadistica form", function () {

	let self = $(this);
	let form = new FormData(this);
	let data = {
		fecha_min: form.get('fecha-min'),
		fecha_max: form.get('fecha-max')
	}

	let type = self.parents("div:first").attr("class");

	type = type.split("-");
	type = type[type.length -1];

	$.ajax({
		url: `${domain}/api/reportes/${type}`,
		data: data,
		success: rsp => {

			try { 
				rsp = JSON.parse(rsp);
			} catch (e) {
				showDialog({
		            title: 'Fallo',
		            text: rsp,
		            negative: {
		                title: 'Cerrar'
		            }
		        });

		        console.error(e);
				return;
			}

			if (rsp.code != 200) {
				showDialog({
		            title: 'Fallo',
		            text: rsp.data,
		            negative: {
		                title: 'Cerrar'
		            }
		        });
				return;
			}

	        let div = $("div.reporte-data");

	        div.fadeOut(200, () => {
	        	div.empty();
	        	div.html(rsp.data);
	        	div.fadeIn(200);
	        });
		}
	});
});

$(document).on("click", "p.remove-before", function () {

	let self = $(this);

	self.prevAll("input[type='text']:first").val("")
});

$(document).on("click", "input.print-report", function () {

	let self = $(this);
	let data = self.prev();

	let html = "<html>" + $("head").html();
	html += "<body style='overflow: visible; display: flex' onload='window.print(); window.close();'><div class='container'>"+data.html()+"</div></body></html>";

	let doc = window.open("","Print","width=600,height=600,scrollbars=1,resizable=1");

	doc.document.open();
	doc.document.write(html);
	doc.document.close();
});