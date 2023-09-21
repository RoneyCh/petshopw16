<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "Usuario";
$route['404_override'] = '';
$route['Usuario/showCreate'] = 'Usuario/showCreate';
$route['Usuario/login'] = 'Usuario/login';
$route['Home'] = 'Home';
$route['Usuario/logout'] = 'Usuario/logout';
$route['Usuario/listUsuarios'] = 'Usuario/listUsuarios';
$route['Usuario/atualizarUsuario'] = 'Usuario/atualizarUsuario';
$route['Usuario/criarUsuario'] = 'Usuario/criarUsuario';
$route['Usuario/deletarUsuario'] = 'Usuario/deletarUsuario';
$route['Pets/listPets'] = 'Pets/listPets';
$route['Pets/showCreate'] = 'Pets/showCreate';
$route['Pets/atualizarPet'] = 'Pets/atualizarPet';
$route['Pets/criarPet'] = 'Pets/criarPet';
$route['Pets/deletarPet'] = 'Pets/deletarPet';
$route['Agendamentos/listAgendamentos'] = 'Agendamentos/listAgendamentos';
$route['Agendamentos/showCreate'] = 'Agendamentos/showCreate';
$route['Agendamentos/atualizarAgendamento'] = 'Agendamentos/atualizarAgendamento';
$route['Agendamentos/criarAgendamento'] = 'Agendamentos/criarAgendamento';
$route['Agendamentos/deletarAgendamento'] = 'Agendamentos/deletarAgendamento';


/* End of file routes.php */
/* Location: ./application/config/routes.php */