
# --------------------------------------------
# SSH
# --------------------------------------------
set :ssh_options, {
  :user => "root",
  :password =>"taketomo",
  :port => 22
}
#ssh_options[:keys] = %w(/home/user/.ssh/private_key) # SSH key
#ssh_options[:port] = 22

set :application, "pluspanda"

# --------------------------------------------
# Version Control
# --------------------------------------------
default_run_options[:pty] = true
set :scm, 'git'
set :repository,  "git@github.com:plusjade/pluspanda.git"
set :deploy_via, :remote_cache
set :branch, 'master'
set :git_shallow_clone, 1
set :scm_verbose, true
set :scm_command, "/usr/bin/git" 
set :local_scm_command, "git" 
#set :scm_passphrase, "p@ssw0rd" #This is your custom users password
#set :user, "deployer"


role :web, "72.14.179.155"   # Your HTTP server, Apache/etc
#role :app, "your app-server here"                          # This may be the same as your `Web` server
#role :db,  "your primary db-server here", :primary => true # This is where Rails migrations will run
#role :db,  "your slave db-server here"


set :deploy_to, "/staging/pluspanda"

# namespace :deploy do
#   task :start {}
#   task :stop {}
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end

# MYCUSTOM VARS
set :db_version , 3



desc "Staging: This will update the symlink"
task :staging do
  run "ln -nfs #{release_path} #{current_path}"
  
  # overwrite localhost files with deployment ones.
  run "mv -f #{release_path}/_deploy/staging/application/config/config.php #{release_path}/application/config/config.php"
  run "mv -f #{release_path}/_deploy/staging/application/config/database.php #{release_path}/application/config/database.php"
  
  # Symlink the shared directories to the directories in your application.
  run "if [ -d #{release_path}/public/data ] ; then rm -rf #{release_path}/public/data  ; fi ;"
  run "ln -s #{shared_path}/data #{latest_release}/public/data"  
  run "ln -s #{shared_path}/logs #{latest_release}/application/logs"

  # chmod the files and directories.
  run "find #{shared_path} -type d -exec chmod 0777 {} \\;"
  run "find #{latest_release} -type d -exec chmod 0755 {} \\;"
  run "find #{latest_release} -type f -exec chmod 644 {} \\;"

  
  # mirror production db at staging db
  run "mysqldump -q -uroot -pgenius12 pluspanda > /backup/pluspanda.sql"
  run "mysqladmin -uroot -pgenius12 --force DROP pluspanda_staging"
  run "mysqladmin -uroot -pgenius12 CREATE pluspanda_staging"  
  run "mysql -uroot -pgenius12 pluspanda_staging < /backup/pluspanda.sql"

  # if exists, apply the db version file to update db.
  run "if [ -e #{release_path}/_db/#{db_version}.sql ] ; then mysql -uroot -pgenius12 pluspanda_staging < #{release_path}/_db/#{db_version}.sql ; fi ;"
  
  # finally run the updater controller, then remove it.
  run "wget --spider http://stfugid.com/updater?pw=willow"
  run "unlink #{release_path}/application/controllers/updater.php"
end


desc "Production: This will update the symlink"
task :production do

  run "ln -nfs #{release_path} #{current_path}"
  
  # overwrite localhost files with deployment ones.
  run "mv -f #{release_path}/_deploy/production/application/config/config.php #{release_path}/application/config/config.php"
  run "mv -f #{release_path}/_deploy/production/application/config/database.php #{release_path}/application/config/database.php"
  
  # Symlink the shared directories to the directories in your application.
  run "ln -s #{shared_path}/logs #{latest_release}/application/logs"

  # chmod the files and directories.
  run "find #{shared_path} -type d -exec chmod 0777 {} \\;"
  run "find #{latest_release} -type d -exec chmod 0755 {} \\;"
  run "find #{latest_release} -type f -exec chmod 644 {} \\;"

  
  # backup the database again.
  run "mysqldump -q -uroot -pgenius12 pluspanda > /backup/pluspanda.sql"

  # if exists, apply the db version file to update db.
  run "if [ -e #{release_path}/_db/#{db_version}.sql ] ; then mysql -uroot -pgenius12 pluspanda < #{release_path}/_db/#{db_version}.sql ; fi ;"
  
  # finally run the updater controller.
  run "wget --spider http://pluspanda.com/updater?pw=willow"
  run "unlink #{release_path}/application/controllers/updater.php"
end


task :backup_db do
  run "mysqldump -q -uroot -pgenius12 pluspanda > /backup/pluspanda.sql"
  run "cd /backup  
    git add . 
    git commit 'updating pluspanda db =d' 
    git push origin master"
 end
 
 
# hooks
# Change the hook depending on what deployment weare doing.
after "deploy:update", :staging









