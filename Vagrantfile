    # -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "boxcutter/debian85"

  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "forwarded_port", guest: 8081, host: 8081

  config.vm.synced_folder ".", "/vagrant",  :owner=> 'www-data', :group=>'users', :mount_options => ['dmode=777', 'fmode=777']

  config.vm.provider "virtualbox" do |vb|
     # Display the VirtualBox GUI when booting the machine
     vb.gui = false

    # Customize the amount of memory on the VM:
    vb.memory = "1024"
  end


  config.vm.provision :shell, path: "vagrant/bootstrap.sh"

end
