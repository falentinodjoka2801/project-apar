class AddDataUser extends React.Component{
     state     =    {
          nama      :    '',
          alamat    :    '',
          nomorTelepon   :    '',
          email     :    '',
          username  :    '',
          password  :    '',
          konfirmasiPassword  :    '',
          level     :    'admin',
          showPassword   :    false,
          showKonfirmasiPassword  :    false
     }
     
     simpanPerubahan     =    ()   =>   {
          let tS    =    this.state;
          let tP    =    this.props;

          let btnSimpan  =    $('#simpanDataUser');
          let data  =    {
               nama      :    tS.nama,
               alamat    :    tS.alamat,
               nomorTelepon   :    tS.nomorTelepon,
               email      :    tS.email,
               username   :    tS.username,
               password       :    tS.password,
               konfirmasiPassword  :    tS.konfirmasiPassword,
               level     :    tS.level
          };

          btnSimpan.prop('disabled', true);
          btnSimpan.text('Processing ..');

          $.ajax({
               url  :    `${basicURL}User/simpanDataUser`,
               data,
               type :    'POST',
               success   :    (responseFromServer)     =>   {
                    let JSONResponse    =    JSON.parse(responseFromServer);

                    let type, message;
                    if(JSONResponse.statusPenyimpanan === true){
                         type      =    'success';
                         message   =    'Berhasil menyimpan data user !';
                    }else{
                         type      =    'error';
                         message   =    JSONResponse.message;
                    }

                    Swal.fire({
                         title     :    'Penyimpanan Data User',
                         type,
                         html      :    `<b class='${(type === 'success')? 'text-success' : 'text-danger'}'>${message}</b>`
                    }).then(() => {
                         btnSimpan.prop('disabled', false);
                         btnSimpan.text('Simpan');

                         
                         if(JSONResponse.statusPenyimpanan === true){
                              window.location.href     =    `${basicURL}User`;    
                         }
                    });
               }
          });
     }
     render    =    ()   =>   {
          let tS                   =    this.state;
          
          return(
               <div className="row">
                    <div className="col-lg-12">
                         <div className="card">
                              <div className="card-header border-bottom">
                                   <div className="row">
                                        <div className="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-left col">Tambah Data User</div>
                                        <div className="col-lg-8 col-md-8 col-sm-6 col-xs-6 text-right col">
                                             <a href={`${basicURL}User`}>
                                                  <span className={'material-icons cp text-danger'}>close</span>
                                             </a>
                                        </div>
                                   </div>
                              </div>
                              <div className="card-body">
                                   <div className={'row'}>
                                        <div className={'col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2'}>
                                             <label htmlFor={'nama'} className={'text-sm'}>Nama User *</label>
                                             <input type={'text'} name={'nama'} id={'nama'} 
                                                  placeholder={'Nama User'}
                                                  className={'form-control'} value={tS.nama} 
                                                  onChange={(e) => this.setState({nama : e.target.value})} />
                                        </div>
                                        <div className={'col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2'}>
                                             <label htmlFor={'nomorTelepon'} className={'text-sm'}>Nomor Telepon User *</label>
                                             <input type={'number'} name={'nomorTelepon'} id={'nomorTelepon'} 
                                                  placeholder={'Nomor Telepon User'}
                                                  className={'form-control'} value={tS.nomorTelepon} 
                                                  onChange={(e) => this.setState({nomorTelepon : e.target.value})} />
                                        </div>
                                        <div className={'col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2'}>
                                             <label htmlFor={'email'} className={'text-sm'}>Email User *</label>
                                             <input type={'email'} name={'email'} id={'email'} 
                                                  placeholder={'Email User'}
                                                  className={'form-control'} value={tS.email} 
                                                  onChange={(e) => this.setState({email : e.target.value})} />
                                        </div>
                                   </div>
                                   <div className={'row'}>
                                        <div className={'col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2'}>
                                             <label htmlFor={'username'} className={'text-sm'}>Username *</label>
                                             <input type={'text'} name={'username'} id={'username'} 
                                                  placeholder={'Username'}
                                                  className={'form-control'} value={tS.username} 
                                                  onChange={(e) => this.setState({username : e.target.value})} />
                                        </div>
                                        <div className={'col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-2'}>
                                             <label htmlFor={'password'} className={'text-sm'}>Password *</label>
                                             <div className={'input-group'}>
                                                  <input type={(tS.showPassword)? 'text' : 'password'} name={'password'} id={'password'} 
                                                       placeholder={'Password'}
                                                       className={'form-control'} value={tS.password} 
                                                       onChange={(e) => this.setState({password : e.target.value})} />
                                                  <div className={'input-group-append'}>
                                                       <span className={'input-group-text'}>
                                                            <span className={'material-icons cp'} style={{fontSize:'12pt'}}
                                                                 onClick={() => this.setState({showPassword : !tS.showPassword})}>
                                                                      {(tS.showPassword)? 'visibility_off' : 'visibility'}
                                                            </span>
                                                       </span>
                                                  </div>
                                             </div>
                                        </div>
                                        <div className={'col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-2'}>
                                             <label htmlFor={'konfirmasiPassword'} className={'text-sm'}>Konfirmasi Password *</label>
                                             <div className={'input-group'}>
                                                  <input type={(tS.showKonfirmasiPassword)? 'text' : 'password'} name={'konfirmasiPassword'} id={'konfirmasiPassword'} 
                                                       placeholder={'Konfirmasi Password'}
                                                       className={'form-control'} value={tS.konfirmasiPassword} 
                                                       onChange={(e) => this.setState({konfirmasiPassword : e.target.value})} />
                                                  <div className={'input-group-append'}>
                                                       <span className={'input-group-text'}>
                                                            <span className={'material-icons cp'} style={{fontSize:'12pt'}}
                                                                 onClick={() => this.setState({showKonfirmasiPassword : !tS.showKonfirmasiPassword})}>
                                                                      {(tS.showKonfirmasiPassword)? 'visibility_off' : 'visibility'}
                                                            </span>
                                                       </span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div className={'row'}>
                                        <div className={'col-lg-2 col-md-2 col-sm-12 col-xs-12 mb-2'}>
                                             <label htmlFor={'level'} className={'text-sm'}>Level (Hak Akses) *</label>
                                             <select name={'level'} id={'level'} 
                                                  className={'form-control'} value={tS.level} 
                                                  onChange={(e) => this.setState({level : e.target.value})}>
                                                       <option value={'admin'}>Admin</option>
                                                       <option value={'superadmin'}>Super Admin</option>
                                             </select>
                                        </div>
                                        <div className={'col-lg-10 col-md-10 col-sm-12 col-xs-12 mb-2'}>
                                             <label htmlFor={'alamat'} className={'text-sm'}>Alamat *</label>
                                             <textarea name={'alamat'} id={'alamat'} 
                                                  placeholder={'Alamat'}
                                                  className={'form-control'} value={tS.alamat} 
                                                  onChange={(e) => this.setState({alamat : e.target.value})}></textarea>
                                        </div>
                                   </div>
                                   <hr />
                                   <div className={'row'}>
                                        <div className={'col-12'}>
                                             <button className={'btn btn-success btn-pill mr-1'} onClick={() => this.simpanPerubahan()}
                                                  id={'simpanDataUser'}>Simpan Data User</button>
                                             <a href={`${basicURL}User`}>
                                                  <button className={'btn btn-danger btn-pill ml-1 btn-outline-danger'}>Batal</button>
                                             </a>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          );
     }
}