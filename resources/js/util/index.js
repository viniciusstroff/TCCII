const criarAlertaToast = (componente, titulo, estilo, mensagem, solido = true) => {
    componente.$bvToast.toast(mensagem, {
        title: titulo,
        variant: estilo,
        solid: solido
    })
}

const mapearObjetos = (objetoOrigem, objetoDestino) => {
    return window.$.extend(objetoDestino, objetoOrigem);
}

const criarAlertaToastDeSucesso = (componente, titulo, mensagem) => {
    criarAlertaToast(componente, titulo, 'success', mensagem)
}

const criarAlertaToastDeErro = (componente, titulo, mensagem) => {
    criarAlertaToast(componente, titulo, 'danger', mensagem)
}
export default {
    criarAlertaToast,
    criarAlertaToastDeErro,
    criarAlertaToastDeSucesso,
    mapearObjetos

}