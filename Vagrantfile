# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.define "ubuntu", primary: true do |ubuntu|
  	  ubuntu.vm.box = "ubuntu/trusty64"
  	  ubuntu.vm.provision :shell, path: "vagrant/provision.sh"
  	  ubuntu.vm.hostname = "sicopla.local"
  	  ubuntu.vm.network "private_network", ip: "192.168.56.162"
  	  ubuntu.hostsupdater.aliases = ["sicopla.local"]
  	  ubuntu.vm.synced_folder ".", "/vagrant", {:owner => "www-data", :group => "www-data"}
  end

  config.vm.provider :virtualbox do |vb|
      vb.customize ["modifyvm", :id, "--memory", "1024"]
      vb.customize ["modifyvm", :id, "--cpus", 2]
  end

end
