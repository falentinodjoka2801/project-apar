class ListDataUser extends React.Component{
     state     =    {
          listDataUser   :    [],
          listDataUserImmutable    :    []
     }
     componentDidMount   =    ()   =>   {
          let getListDataUser =    axios.get(`${basicURL}User/listDataUser`)
               .then((responseFromServer) => {
                    let listDataUser   =    responseFromServer.data.listDataUser;

                    this.setState({
                         listDataUser             :    listDataUser,
                         listDataUserImmutable    :    listDataUser
                    });
               }).catch((error) => {
                    alert(error);
               });
          
          $('[data-toggle=tooltip]').tooltip();
     }
     /*
     terapkanFilter      =    ()   =>   {
          let tS         =    this.state;

          let options    =    {
               params    :    {
                    orderByField        :    tS.orderByField,
                    orderByOrientation  :    tS.orderByOrientation,
                    limitData :    tS.limitData
               }
          }

          let getListDataGedung =    axios.get(`${basicURL}Gedung/listDataGedung`, options)
               .then((responseFromServer) => {
                    let listDataGedung   =    responseFromServer.data.listDataGedung;

                    this.setState({
                         listDataGedung             :    listDataGedung,
                         listDataGedungImmutable    :    listDataGedung
                    });
               }).catch((error) => {
                    alert(error);
               });
     }
     */
     cariData  =    (str)     =>   {
          let tS    =    this.state;
          str  =    str.toLowerCase();

          let filteredData;

          if(str !== ''){
               filteredData    =    tS.listDataUserImmutable.filter((data) => {
                    let nama       =    data.nama.toLowerCase();
                    let username   =    data.username.toLowerCase();
                    let email      =    data.email.toLowerCase();
                    let nomorTelepon      =    data.nomorTelepon.toLowerCase();

                    return    nama.indexOf(str) >= 0 || username.indexOf(str) >= 0 || email.indexOf(str) >= 0 || nomorTelepon.indexOf(str) >= 0;
               });
          }else{
               filteredData   =    tS.listDataUserImmutable;
          }

          this.setState({listDataUser : filteredData});
     }
     render    =    ()   =>   {
          let tS    =    this.state;

          let rows;

          if(tS.listDataUser.length >= 1){
               rows      =    tS.listDataUser.map((dataUser, index) => {
                                   return    <RowUser key={index} index={index+1} data={dataUser} 
                                                  reloadDataUser={() => this.componentDidMount()} />
                              });
          }else{
               rows      =    <TRDataNotFound title={'Data Tidak Ditemukan'} desc={'Data tidak ditemukan berdasarkan pencarian !'} 
                                   colSpan={7} />;
          }
          
          // let fieldOrderData  =    [
          //      {text : 'Kode Gedung', value : 'kodeGedung'},
          //      {text : 'Nama Gedung', value : 'namaGedung'}
          // ];
     
          return(
               <React.Fragment>
                    {false &&
                         <div className="row mb-3">
                              <div className="col-lg-12">
                                   <div className="card">
                                        <div className="card-header border-bottom">
                                             <div className="row">
                                                  <div className="col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2 text-left col">Filter Data Gedung</div>
                                                  <div className="col-lg-8 col-md-8 col-sm-12 col-xs-12  mb-2 text-right col">
                                                       <span className="material-icons cp text-primary"
                                                            data-toggle='dropdown'>more</span>
                                                       <div className="dropdown-menu dropdown-menu-right">
                                                            <li className="dropdown-item"><a href={`${basicURL}Gedung/addGedung`}>Add Data Gedung</a></li>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div className="card-body">
                                             <div className={'row pb-1'}>
                                                  <div className={'col-lg-3'}>
                                                       <OrderData componentValue={(selectedField, selectedOrientation) => {
                                                                 this.setState({orderByField : selectedField, orderByOrientation : selectedOrientation})
                                                            }} fields={fieldOrderData} label={'Order Data'} />
                                                  </div>
                                                  <div className={'col-lg-3'}>
                                                       <LimitData componentValue={(limitData) => this.setState({limitData})}
                                                            label={'Limit Data'}/>
                                                  </div>
                                             </div>
                                             <hr />
                                             <button className={'btn btn-success btn-pill'}
                                                  onClick={() => this.terapkanFilter()}>Terapkan Filter</button>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    }
                    <div className="row">
                         <div className="col-lg-12">
                              <div className="card">
                                   <div className="card-header border-bottom">
                                        <div className="row">
                                             <div className="col-lg-4 col-md-12 col-sm-12 col-xs-12 text-left col">List Data User</div>
                                             <div className="col-lg-8 col-md-12 col-sm-12 col-xs-12 text-right col">
                                                  <div className={'row'}>
                                                       <div className={'col-11 col'}>
                                                            <Search doSearch={(str) => this.cariData(str)} 
                                                                 placeholder={'Cari Data User menggunakan Nama, Username, Nomor Telepon atau Email'} />
                                                       </div>
                                                       <div className={'col-1 col text-right'}>
                                                            <a href={`${basicURL}User/addUser`}>
                                                                 <span className={'material-icons cp text-success'}
                                                                      data-toggle={'tooltip'} data-placement={'top'}
                                                                      title={'Tambah Data User'}>add</span>
                                                            </a>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div className="card-body">
                                        <div className="table-responsive">
                                             <table className="table table-bordered table-striped" id='tabelUser'>
                                                  <thead>
                                                       <tr>
                                                            <th className="vam text-center">No.</th>
                                                            <th className="vam text-left">Nama</th>
                                                            <th className="vam text-left">Level</th>
                                                            <th className="vam text-left">No. Telp</th>
                                                            <th className="vam text-left">Email</th>
                                                            <th className="vam text-left">Username</th>
                                                            <th className="vam text-center">Opsi</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       {rows}
                                                  </tbody>
                                             </table>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </React.Fragment>
          );
              
     }
}