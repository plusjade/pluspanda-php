# --------------------------------------------
# General
# --------------------------------------------
set :shared_children, %w(cache logs) # Shared directories, these directories contain generated content which should not be wiped out during deployments.
set :application, "pluspanda" # Application name
set :deploy_to,   "/staging" # Path where files are deployed to ...

# --------------------------------------------
# Server
# --------------------------------------------
# Commands on the server are run as the following user :
#set :runner,   "website"
#set :user,     "website"
set :use_sudo, false # sudo isn't required for my deployment.

# --------------------------------------------
# Repository
# --------------------------------------------
set :scm, 'git'
set :repository,  "git@github.com:plusjade/kohana-fizzle.git"
set :deploy_via, :remote_cache
set :branch, 'master'
set :git_shallow_clone, 1
set :scm_verbose, true

#72.14.179.155
role :web, "#{application}"

# --------------------------------------------
# SSH
# --------------------------------------------
ssh_options[:keys] = %w(/home/user/.ssh/private_key) # SSH key
ssh_options[:port] = 22




# --------------------------------------------
# Overloaded Methods.
# --------------------------------------------
namespace :deploy do

task :finalize_update, :except => { :no_release => true } do
    # Make sure the log and upload directories do not exist.
    run "rmdir #{latest_release}/application/logs"
    run "rmdir #{latest_release}/application/cache"
	 
    # Symlink the shared directories to the directories in your application.
    run "ln -s #{shared_path}/logs #{latest_release}/application/logs"
    run "ln -s #{shared_path}/cache #{latest_release}/application/cache"

    # chmod the files and directories.
    run "find #{shared_path} -type d -exec chmod 0755 {} \\;"
    run "find #{latest_release} -type d -exec chmod 0755 {} \\;"
    run "find #{latest_release} -type f -exec chmod 644 {} \\;"
  end

  namespace :web do
    task :disable do
      # When cap deploy:web:disable is run, copy a maintenance.html page from the shared directory
      # to the webroot. You will typically have Apache check for this file and disable access to the 
      # site if it exists.
      run "cp #{shared_path}/maintenance.html #{latest_release}"
      run "echo #{stage}"
    end
    task :enable do
      run "rm -f #{latest_release}/maintenance.html"
    end
  end
end

# Hook the web disable and disable events into the deployment.
after "deploy:update_code", "deploy:web:disable"
after "deploy:symlink", "deploy:web:enable"
