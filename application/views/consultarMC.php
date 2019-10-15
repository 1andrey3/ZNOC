<div class="main-title">
Consultar Bitacoras Mesa De Calidad
</div>


<div style="display:flex; justify-content: center;">
  <div class="card-style w-60">
    <div class="general">

          <div class="col-md-6 position-relative switch-container ">
            <div class="form-group">
              <label class="form-label"> Tipo de bitácora</label>
              <select id="bitac" name="opcion" class="form-control form-input required-field">
                <option value="">Seleccione...</option>
              </select>
            </div>
          </div>


          <div class="switch-container col-md-6 position-relative form-group" style="margin-top: 4px">
            <label class="switch">
              <input id="toggleDate" class="checkbox form-check-input" type="checkbox" value="" >
              <span class="slider round"></span>
            </label>
            <span class="checkbox-initial">
              Filtro inicio de actividad:
            </span>
          </div>


          <div class="col-md-6 col-body">
            <div class="form-group">
              <label class="form-label">Desde:</label>
              <input type="" id="dateInitial" class="form-control form-input required-field">
            </div>
          </div>

          <div class="col-md-6 col-body">
            <div class="form-group">
              <label class="form-label">Hasta:</label>
              <input type="" id="finalDay" class="form-control form-input required-field">
            </div>
          </div>

          <div class="col-md-12 col-body">
            <div class="wrap" style="margin: auto;">
              <button type="submit" id="buscar" class="btnx" >Buscar</button>
              <div id="#">
                <img src="https://www.dropbox.com/s/qfu4871umzhlcfo/check_arrow_2.svg?dl=1">
                <svg width="66px" height="66px">
                  <circle class="circle_2" stroke-position="outside" stroke-width="3" fill="none" cx="34" cy="33" r="29" stroke="#1ECD97"></circle>
                </svg>
              </div>
            </div>
          </div>

    </div>
  </div>
</div>
