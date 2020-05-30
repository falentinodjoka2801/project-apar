class ListDataGedung extends React.Component{
     state     =    {
          listDataGedung   :    [],
          listDataGedungImmutable    :    [],
          orderByField   :    '',
          orderByOrientation  :    '',
          limitData :    10
     }
     componentDidMount   =    ()   =>   {
          let getListDataGedung =    axios.get(`${basicURL}Gedung/listDataGedung`)
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
     cariData  =    (str)     =>   {
          let tS    =    this.state;
          str  =    str.toLowerCase();

          let filteredData;

          if(str !== ''){
               filteredData    =    tS.listDataGedungImmutable.filter((data) => {
                    let namaGedung =    data.namaGedung.toLowerCase();
                    let kodeGedung =    data.kodeGedung.toLowerCase();

                    return    namaGedung.indexOf(str) >= 0 || kodeGedung.indexOf(str) >= 0;
               });
          }else{
               filteredData   =    tS.listDataGedungImmutable;
          }

          this.setState({listDataGedung : filteredData});
     }
     render    =    ()   =>   {
          let tS    =    this.state;

          let rows;

          if(tS.listDataGedung.length >= 1){
               rows      =    tS.listDataGedung.map((dataGedung, index) => {
                                   return    <RowGedung key={index} index={index+1} data={dataGedung} 
                                                  reloadDataGedung={() => this.componentDidMount()} />
                              });
          }else{
               rows      =    <TRDataNotFound title={'Data Tidak Ditemukan'} desc={'Data tidak ditemukan berdasarkan pencarian !'} 
                                   colSpan={4} />;
          }
          
          let fieldOrderData  =    [
               {text : 'Kode Gedung', value : 'kodeGedung'},
               {text : 'Nama Gedung', value : 'namaGedung'}
          ];
     
          return(
               <React.Fragment>
                    <div className="row mb-3">
                         <div className="col-lg-12">
                              <div className="card">
                                   <div className="card-header border-bottom">
                                        <div className="row">
                                             <div className="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-left col">Filter Data Gedung</div>
                                             <div className="col-lg-8 col-md-8 col-sm-6 col-xs-6 text-right col">
                                                  <a href={`${basicURL}Gedung/addGedung`}>
                                                       <span className={'material-icons cp text-success'}
                                                            data-toggle={'tooltip'} data-placement={'top'}
                                                            title={'Tambah Data Gedung'}>add</span>
                                                  </a>
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
                                             <div className="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-left">List Data Gedung</div>
                                             <div className="col-lg-8 col-md-8 col-sm-6 col-xs-6 text-right">
                                                  <Search doSearch={(str) => this.cariData(str)} 
                                                       placeholder={'Cari Data Gedung menggunakan Nama Gedung atau Kode Gedung'} />
                                             </div>
                                        </div>
                                   </div>
                                   <div className="card-body">
                                        <div className="table-responsive">
                                             <table className="table table-bordered table-striped" id='tabelGedung'>
                                                  <thead>
                                                       <tr>
                                                            <th className="vam text-center">No.</th>
                                                            <th className="vam text-center">Kode Gedung</th>
                                                            <th className="vam text-center">Nama Gedung</th>
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