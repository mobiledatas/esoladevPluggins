const esolaAppSocialLawyers = document.querySelector("#esolaAppSocialLawyers");
let tbody = esolaAppSocialLawyers.querySelector('#tbody-social');
let refreshBtn = esolaAppSocialLawyers.querySelector('#refreshSocial');
let titleForm = esolaAppSocialLawyers.querySelector('#titleform');
let resetBtn = esolaAppSocialLawyers.querySelector('#resetBtn');
let form = esolaAppSocialLawyers.querySelector('#socialFormEsola');
let actionBtn = form.elements['actionBtn'];

form.addEventListener('submit', async function (e) {
    e.preventDefault();
    let state = form.getAttribute('data-state');
    let data = {
        'action': state == 'new' ? 'create_social_esola' : 'edit_social_esola',
        post_id: form.elements['post_id'].value,
        name: form.elements['name'].value,
        link: form.elements['link'].value,
        id: form.elements['id'].value,
        lang: form.elements['lang'].value,
    };
    if(state == 'edit'){
        await $update(data);
        form.reset();
    }
    if(state == 'new'){
        await $insert(data);
        form.reset();
    }
    form.setAttribute('data-state','new');
    
});

refreshBtn.addEventListener('click', async function () {
    await hidrateTable();
});

resetBtn.addEventListener('click', async function () {
    form.reset();
    form.setAttribute('data-state', 'new');
});
tbody.addEventListener('click', async function (e) {
    handlerBtns(e);
});

async function handlerBtns(e) {
    if (e.target.nodeName === "BUTTON") {
        let btn = e.target;
        if (btn.name == "editBtn") {
            form.setAttribute('data-state', 'edit');
            titleForm.innerHTML = 'Cargando ...';
            resetBtn.innerHTML = 'Nuevo';
            actionBtn.setAttribute('disabled', '');
            btn.setAttribute('disabled', 'disabled');
            let item = await $get(btn.getAttribute('data-id'));
            titleForm.innerHTML = 'Editando red social para abogado ';
            form.elements['id'].value = item[0].id;
            form.elements['post_id'].value = item[0].post_id;
            form.elements['name'].value = item[0].name;
            form.elements['link'].value = item[0].link;
            btn.removeAttribute('disabled');
            actionBtn.removeAttribute('disabled');
        }
        if (btn.name == "deleteBtn") {
            //console.log("prueba delete");
            /*
            1-abrir un modal de confirmacion
            2-colocar estado del boton a eliminando DEHABILITAR BOTON Y TABLA
            3-validamos respuesta, en caso de error, notificar
            4-refrescar tabla           
            */ 
           let id=btn.getAttribute('data-id');
           let rpt=confirm("Estas segur@ de eliminar el registro de id:"+id);
           //console.log(rpt);
           if(rpt==true){
                try{
                    btn.setAttribute('disabled','disabled');
                    btn.innerHTML='Eliminando...';
                    titleForm.innerHTML = 'Eliminando red social de abogado ';
                    let res=await $delete(id);
                    if(res.affectedItems>0){
                        alert("Se elimino satisfactoriamente");
                    }
                    if(res.affectedItems<=0){
                        alert("No se pudo eliminar el registro");
                    }
                    if(!res.affectedItems){
                        alert(res);                        
                    }
                    btn.removeAttribute('disabled');
                    btn.innerHTML='Eliminar';
                    titleForm.innerHTML = 'Asignar nueva red social a abogado';
                    await hidrateTable();
                }catch (ex){
                    alert(ex);
                }
           }
        }
    }

}

async function hidrateTable() {    
    tbody.innerHTML = `<tr><td colspan="4"> Refrescando ... </td></tr>`;
    let data = await getAllSocial();
    result = refreshTable(data);
    tbody.innerHTML = result;
}

async function getAllSocial() {
    let res = null;
    data = {
        'action': 'all_social_esola',
        lang:form.elements['lang'].value
    };
    await jQuery.post(ajaxurl, data, async function (response) {
        res = await (response).data
    });
    return res;
}

function refreshTable(data) {
    //console.log(data);
    let content = '';
    data.forEach((s) => {
        content = content.concat( `
            <tr>
                <td>${s.id}</td>
                <td>${s.name}</td>
                <td>${s.post_title}</td>
                <td><a target="_blank" href="${s.link}">${s.link}</a></td>
                <td><button name="editBtn" data-id="${s.id}">Editar</button></td>
                <td><button name="deleteBtn" data-id="${s.id}">Eliminar</button></td>
            </tr>
        `);
    });
    
    return content;
}

async function $insert(data) {
    actionBtn.setAttribute('disabled','');
    jQuery.post(ajaxurl, data, async function (r) {
        let id = JSON.parse(r).response.lastId;
        if (id != null && id > 0) {
            await hidrateTable();
            actionBtn.removeAttribute('disabled');
        }
    })
}

async function $update(data) {
    actionBtn.setAttribute('disabled','');
    jQuery.post(ajaxurl, data, async function (r) {
        let affectedItems = JSON.parse(r).response.affectedItems;
        if(affectedItems>=0){
            await hidrateTable();
            actionBtn.removeAttribute('disabled');
        }
    })
}
async function $get(id) {
    let res;
    let data = {
        action: 'get_social_esola',
        id: id
    }
    await jQuery.post(ajaxurl, data, function (r) {
        res = (r).social;
    });
    return res;
}

async function $delete(id){
    let res;
    let data={
        action: 'delete_social_esola',
        id: id
    }
    await jQuery.post(ajaxurl, data, function(r2){
        res=JSON.parse(r2).response;
    });
    return res;
}