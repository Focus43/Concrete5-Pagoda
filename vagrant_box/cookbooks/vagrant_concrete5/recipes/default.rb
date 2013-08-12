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

# install node dependencies (uses package.json file in node_grunt)
execute "npm dependencies installation" do
	cwd "/home/vagrant/app/build/"
	user "root"
	command "/usr/local/bin/npm install; /usr/local/bin/npm install -g grunt-cli"
	action :run
end
