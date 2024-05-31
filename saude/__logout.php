<?
session_start();
require_once("conexao.php");

grava_acesso($_SESSION["id_usuario_sessao"], $_SESSION["id_posto_sessao"], $_SESSION["id_cidade_sessao"], 's', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "dá logout", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);

session_unregister("id_usuario_sessao");
session_unregister("tipo_usuario_sessao");
session_unregister("nome_pessoa_sessao");
session_unregister("id_posto_sessao");
session_unregister("id_cidade_sessao");
session_unregister("id_cidade_pref");
session_unregister("id_uf_pref");
session_unregister("permissao");
session_unregister("trocando");
session_unregister("id_cbo_sessao");
session_unregister("id_acesso");

header("location: index2.php?pagina=login");
?>