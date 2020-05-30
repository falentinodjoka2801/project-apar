class ListDataAPAR extends React.Component{
     state     =    {
          listDataAPAR   :    [],
          listDataAPARImmutable    :    [],
          activeButton   :    '',
          orderByField   :    '',
          orderByOrientation  :    '',
          limitData :    10
     }
     componentDidMount   =    ()   =>   {
          let getListDataAPAR =    axios.get(`${basicURL}APAR/listAPAR`)
               .then((responseFromServer) => {
                    let listAPAR   =    responseFromServer.data.listAPAR;

                    this.setState({
                         listDataAPAR             :    listAPAR,
                         listDataAPARImmutable    :    listAPAR
                    });
               }).catch((error) => {
                    alert(error);
               });
     }
     terapkanFilter      =    ()   =>   {
          let tS         =    this.state;

          let options    =    {
               params    :    {
                    orderByField        :    tS.orderByField,
                    orderByOrientation  :    tS.orderByOrientation,
                    limitData :    tS.limitData
               }
          }

          let getListDataAPAR =    axios.get(`${basicURL}APAR/listAPAR`, options)
               .then((responseFromServer) => {
                    let listAPAR   =    responseFromServer.data.listAPAR;

                    this.setState({
                         listDataAPAR             :    listAPAR,
                         listDataAPARImmutable    :    listAPAR
                    });
               }).catch((error) => {
                    alert(error);
               });
     }
     cariData  =    (str)     =>   {
          let tS    =    this.state;
          str  =    str.toLowerCase();

          let filteredData;

          if(str !== ''){
               filteredData    =    tS.listDataAPARImmutable.filter((data, indexData) => {
                    let qrCode     =    data.qrCode.toLowerCase();
                    let nomorTabung     =    data.nomorTabung.toLowerCase();

                    return    qrCode.indexOf(str) >= 0 || nomorTabung.indexOf(str) >= 0;
               });
          }else{
               filteredData   =    tS.listDataAPARImmutable;
          }

          this.setState({listDataAPAR : filteredData});
     }
     render    =    ()   =>   {
          let tS    =    this.state;

          let rows;

          if(tS.listDataAPAR.length >= 1){
               rows      =    tS.listDataAPAR.map((dataAPAR, index) => {
                                   return    <RowAPAR key={index} index={index+1} data={dataAPAR} 
                                                  reloadDataAPAR={()  => this.componentDidMount()} />
                              });
          }else{
               rows      =    <TRDataNotFound title={'Data Tidak Ditemukan'} desc={'Data tidak ditemukan berdasarkan pencarian !'} 
                                   colSpan={12} />;
          }
          
          let fieldOrderData  =    [
               {text : 'ID Data', value : 'id'},
               {text : 'Nomor Tabung', value : 'nomorTabung'}
          ];
     
          return(
               <React.Fragment>
                    <div className="row mb-3">
                         <div className="col-lg-12">
                              <div className="card">
                                   <div className="card-header border-bottom">
                                        <div className="row">
                                             <div className="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-left col">Filter Data Asset</div>
                                             <div className="col-lg-8 col-md-8 col-sm-6 col-xs-6 text-right col">
                                                  <a href={`${basicURL}APAR/addDataAPAR`}>
                                                       <span className={'material-icons cp text-success'}
                                                            data-toggle={'tooltip'} data-placement={'top'}
                                                            title={'Tambah Data Asset'}>add</span>
                                                  </a>
                                                  <span className="material-icons ml-2 cp text-primary"
                                                       data-toggle='dropdown'>more</span>
                                                  <div className="dropdown-menu dropdown-menu-right">
                                                       <li className="dropdown-item"><a href={`${basicURL}APAR/print/qrcodeperjenisapar/PAR`}>QR Code PAR</a></li>
                                                       <li className="dropdown-item"><a href={`${basicURL}APAR/print/qrcodeperjenisapar/PAB`}>QR Code PAB</a></li>
                                                       <li className="dropdown-item"><a href={`${basicURL}APAR/print/qrcodeperjenisapar/PLR`}>QR Code PLR</a></li>
                                                       <li className="dropdown-item"><a href={`${basicURL}APAR/print/qrcodeperjenisapar/HBX`}>QR Code HBX</a></li>
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
                    <div className="row">
                         <div className="col-lg-12">
                              <div className="card">
                                   <div className="card-header border-bottom">
                                        <div className="row">
                                             <div className="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-left">List Data Asset</div>
                                             <div className="col-lg-8 col-md-8 col-sm-6 col-xs-6 text-right">
                                                  <Search doSearch={(str) => this.cariData(str)} 
                                                       placeholder={'Cari Data Asset menggunakan  QR Code atau Nomor Tabung'} />
                                             </div>
                                        </div>
                                   </div>
                                   <div className="card-body">
                                        <div className="table-responsive">
                                             <table className="table table-bordered table-striped" id='tabelAPAR'>
                                                  <thead>
                                                       <tr>
                                                            <th className="vam text-center" rowSpan={2}>No.</th>
                                                            <th className="vam text-center" rowSpan={2}>KKS</th>
                                                            <th className="vam text-center" rowSpan={2}>Lokasi</th>
                                                            <th className="vam text-center" colSpan={6}>Kriteria</th>
                                                            <th className="vam text-left" rowSpan={2}>Keterangan</th>
                                                            <th className="vam text-left" rowSpan={2}>Last Update</th>
                                                            <th className="vam text-center" rowSpan={2}>Opsi</th>
                                                       </tr>
                                                       <tr>
                                                            <th className="vam text-center">Pressure</th>
                                                            <th className="vam text-center">Nozzle</th>
                                                            <th className="vam text-center">Segel</th>
                                                            <th className="vam text-center">Hose</th>
                                                            <th className="vam text-center">Physically</th>
                                                            <th className="vam text-center">Valve</th>
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