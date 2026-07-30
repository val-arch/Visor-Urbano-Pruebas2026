import pandas as pd

# Load the data excel
data = pd.read_excel('/Users/sergiovillafan/Downloads/Telegram Desktop/scian2023-nuevo.xlsx')

#do a for loop to get the data from the excel
#and print the data in file.txt like this
#  [
#                 ['codigo' => '111110',
#                     'SCIAN' => 'Cultivo de soya',
#                     'palabras_relacion' =>
#                     'frijoles de soya (grano) orgánicos,
#                 frijoles de soya (grano),
#                 soya (grano),
#                 soya bragg (grano),
#                 soya bragg orgánica (grano),
#                 soya cajeme (grano),
#                 soya cajeme orgánica (grano),
#                 soya davis (grano),
#                 soya davis orgánica (grano),
#                 soya hood (grano),
#                 soya hood orgánica (grano),
#                 soya mayo (grano),
#                 soya mayo orgánica (grano),
#                 soya orgánica (grano),
#                 soya Santa Rosa (grano),
#                 soya Santa Rosa orgánica (grano),
#                 soya suprema (grano),
#                 soya suprema orgánica (grano),
#                 soya telabiate (grano),
#                 soya telabiate orgánica (grano),
#                 soya yaqui (grano),
#                 soya yaqui orgánica (grano)','anio_scian' => '2023',
#'bajo_impacto' => true,
#cedula_apertura => true],
#                 ]

txt = '['
#for
for i in range(len(data)):
    #codigo is a entero quitar el .0
    codigo = str(data['codigo'][i]).replace('.0', '')
    txt += f"""
    ['codigo' => '{codigo}',
    'SCIAN' => '{data['scian'][i]}',
    'palabras_relacion' => "{data['palabras_relacion'][i]}",
    'anio_scian' => '{data['anio'][i]}',
    'bajo_impacto' => { 'true' if data['bajo'][i] == 'sí' else 'false'},
    'cedula_apertura' => {'false' }],
    """

txt += ']'

#write the data in file.txt
with open('/Users/sergiovillafan/Downloads/Telegram Desktop/scian-to-php.txt', 'w') as f:
    f.write(txt)