set :application, "OpenHiveManager"
set :domain,      "ssh.web4all.fr"
set :deploy_to,   "/datas/vol3/w4a153895/var/www/openhivemanager.org/htdocs"
set :app_path, "app" 
set :user,        "w4a153895"

set :repository,  "git@git.framasoft.org:jack12/OpenHiveManager.git"
set :branch,      "master"
set :scm, :git 
set :deploy_via, :copy 

set :model_manager, "doctrine" # ORM

role :web, domain
role :app, domain, :primary => true

# Nous utilisons sudo pour régler les permissions via la methode :chown
# préférez l’utilisation des ACLs si c’est disponible sur votre serveur

set :use_sudo, false
set :keep_releases, 3 

## Symfony2
set :shared_files, ["app/config/parameters.yml"] 
set :shared_children, [app_path + "/logs", "vendor"] 
set :use_composer, false
set :update_vendors, false 
set :writable_dirs, ["app/cache", "app/logs"] 
set :webserver_user, "w4a153895"
set :permission_method, :chown 
set :use_set_permissions, true
set :dump_assetic_assets, true

default_run_options[:pty] = true
ssh_options[:forward_agent] = true

logger.level = Logger::MAX_LEVEL
