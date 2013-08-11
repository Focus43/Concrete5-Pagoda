# disable default apache vhost
apache_site "000-default" do
  enable false
end

# setup vhost to link to /home/vagrant/app/web
web_app "default" do
	server_name 'localhost'
	docroot "/home/vagrant/app/web"
	php_timezone 'UTC'
end

# install bundler gem (all subsequent gems managed via bundler)
#execute "Install ruby Bundler gem" do
#	cwd "/home/vagrant/app/"
#	#user "vagrant"
#	command "gem install bundler"
#	action :run
#end

# run Bundler to pull in Gemfile dependencies
execute "Install bundled gems" do
	cwd "/home/vagrant/app/"
	#user "vagrant"
	command "bundle install"
	action :run
end

# install node dependencies (uses package.json file in node_grunt)
execute "npm dependencies installation" do
	cwd "/home/vagrant/app/build/"
	user "root"
	command "/usr/local/bin/npm install; /usr/local/bin/npm install -g grunt-cli"
	action :run
end
