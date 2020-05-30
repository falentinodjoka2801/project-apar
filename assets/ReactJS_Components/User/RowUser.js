class RowUser extends React.Component{
     state     =    {
          view      :    'read',
          nama      :    '',
          alamat    :    '',
          nomorTelepon   :    '',
          email     :    '',
          username  :    '',
          isDeleting     :    false,
          level     :    ''
     }
     componentDidMount   =    ()   =>   {
          let tP    =    this.props;
          let data  =    tP.data;

          this.setState({
               nama : data.nama, 
               alamat : data.alamat, 
               nomorTelepon : data.nomorTelepon, 
               email : data.email, 
               username : data.username,
               level : data.level
          });
          $('[data-toggle=tooltip]').tooltip();
     }
     simpanPerubahan     =    ()   =>   {
          let tS    =    this.state;
          let tP    =    this.props;

          let btnSimpan  =    $('#simpanPerubahan');
          let data  =    {
               idUser    :    tP.data.id,
               nama      :    tS.nama,
               alamat    :    tS.alamat,
               nomorTelepon      :    tS.nomorTelepon,
               email      :    tS.email,
               username   :    tS.username,
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
                              this.setState({view : 'read'})
                              tP.reloadDataUser();
                         }else{
                              let data  =    tP.data;

                              this.setState({
                                   view : 'read',
                                   nama : data.nama, 
                                   alamat : data.alamat, 
                                   nomorTelepon : data.nomorTelepon, 
                                   email : data.email, 
                                   username : data.username
                              });
                         }
                    });
               }
          });
     }
     hapus     =    ()   =>   {
          let tP    =    this.props;
          let data  =    tP.data;

          this.setState({isDeleting : true});
          
          let hapusUser     =    axios.post(`${basicURL}User/hapusUser`, `idUser=${data.id}`)
               .then((responseFromServer) => {
                    let statusDeleteUser   =    responseFromServer.data.statusDeleteUser;
                    let swalMessage, swalType;
                    if(statusDeleteUser){
                         swalMessage    =    'Berhasil Menghapus Data User !';
                         swalType       =    'success';
                    }else{
                         swalMessage    =    'Gagal Menghapus Data User !';
                         swalType       =    'error';
                    }

                    Swal.fire({
                         title     :    'Hapus Data User',
                         text      :    swalMessage,
                         type      :    swalType
                    }).then(() => {
                         if(statusDeleteUser){
                              this.setState({isDeleting : false, view : 'read'});
                              tP.reloadDataUser();
                         }
                    })
               }).catch((error) => alert(error));
     }
     render    =    ()   =>   {
          let tP    =    this.props;
          let tS    =    this.state;
          let data  =    tP.data;
          let index =    tP.index;

          return(
               <tr className={'text-sm'}>
                    {(tS.view === 'read' || tS.view === 'update') &&
                         <React.Fragment>
                              <td className="vam text-center text-bold">{index}.</td>
                              <td className="vam text-left">
                                   {tS.view === 'read' && <h6 className={'mb-0'}>{data.nama}</h6>}
                                   {tS.view === 'update' &&
                                        <input type={'text'} value={tS.nama} onChange={(e) => this.setState({nama : e.target.value})}
                                             className={'form-control form-control-sm'} placeholder={'Nama User'} />
                                   }
                                   {tS.view === 'read' && <p className={'mb-0 mt-1'}>{data.alamat}</p>}
                                   {tS.view === 'update' &&
                                        <input type={'text'} value={tS.alamat} onChange={(e) => this.setState({alamat : e.target.value})}
                                             className={'form-control form-control-sm'} placeholder={'Alamat User'} />
                                   }
                              </td>
                              <td className="vam text-left">
                                   {tS.view === 'read' && data.level}
                                   {tS.view === 'update' &&
                                        <select className={'form-control form-control-sm'} value={tS.level} 
                                             onChange={(e) => this.setState({level : e.target.value})}>
                                             <option value={''}>User Level</option>
                                             <option value={'superadmin'}>Super Admin</option>
                                             <option value={'admin'}>Admin</option>
                                        </select>
                                   }
                              </td>
                              <td className="vam text-left">
                                   {tS.view === 'read' && data.nomorTelepon}
                                   {tS.view === 'update' &&
                                        <input type={'text'} value={tS.nomorTelepon} onChange={(e) => this.setState({nomorTelepon : e.target.value})}
                                             className={'form-control form-control-sm'} placeholder={'Nomor Telepon'} />
                                   }
                              </td>
                              <td className="vam text-left">
                                   {tS.view === 'read' && data.email}
                                   {tS.view === 'update' &&
                                        <input type={'email'} value={tS.email} onChange={(e) => this.setState({email : e.target.value})}
                                             className={'form-control form-control-sm'} placeholder={'Email'} />
                                   }
                              </td>
                              <td className="vam text-left">
                                   {tS.view === 'read' && data.username}
                                   {tS.view === 'update' &&
                                        <input type={'text'} value={tS.username} onChange={(e) => this.setState({username : e.target.value})}
                                             className={'form-control form-control-sm'} placeholder={'Username'} />
                                   }
                              </td>
                              {tS.view === 'read' &&
                                   <td className="vam text-center">
                                        <span className={'text-success material-icons cp icon-option mr-1'}
                                             data-toggle='tooltip' data-placement='top' title='Edit Data' 
                                             onClick={() => this.setState({view : 'update'})}>edit</span>
                                        <span className={'text-danger material-icons cp icon-option ml-1'}
                                             data-toggle='tooltip' data-placement='top' title='Hapus Data'
                                             onClick={() => this.setState({view : 'delete'})}>delete</span>
                                   </td>
                              }
                              {tS.view === 'update' &&
                                   <td className="vam text-center">
                                        <button onClick={() => this.simpanPerubahan()}
                                             className={'btn btn-sm btn-outline-success btn-pill mr-1'} 
                                             id={'simpanPerubahan'}>Simpan</button>
                                        <button onClick={() => this.setState({view : 'read'})} 
                                             className={'btn btn-sm btn-danger btn-pill ml-1'}
                                             onClick={() => this.setState({view : 'read'})}>Cancel</button>
                                   </td>
                              }
                         </React.Fragment>
                    }
                    {tS.view === 'delete' &&
                         <td colSpan={6} className={'text-center py-5'}>
                              <img src={(tS.isDeleting)? `${basicURL}assets/img/loading.gif` : `${basicURL}assets/img/delete.svg`} 
                                   alt={'Delete User'} className={'img-block mb-3'} 
                                   style={{width:'220px'}} />
                              {tS.isDeleting === true &&
                                   <p className={'mb-3 mt-2 text-danger'}>Processing, wait a moment please ..</p>
                              }
                              {tS.isDeleting === false &&
                                   <p className={'mb-3 mt-2 text-danger'}>Anda yakin akan menghapus data user ini ?</p>
                              }
                              <button className={'btn btn-danger btn-pill mr-1'} onClick={() => this.hapus()}
                                   id={'lanjutkanHapus'} disabled={tS.isDeleting}>{(tS.isDeleting)? 'Processing' : 'Lanjutkan Hapus'}</button>
                              {tS.isDeleting === false &&
                                   <button className={'btn btn-success btn-pill ml-1'} 
                                        onClick={() => this.setState({view : 'read'})}>Batal</button>
                              }
                         </td>
                    }
               </tr>
          );
     }
}