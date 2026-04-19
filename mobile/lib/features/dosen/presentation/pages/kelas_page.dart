import 'package:flutter/material.dart';
import 'package:mobile/shared/widgets/dosen/bottom_nav.dart';

class DosenKelasPage extends StatefulWidget {
  const DosenKelasPage({super.key});

  @override
  State<DosenKelasPage> createState() => _DosenKelasPageState();
}

class _DosenKelasPageState extends State<DosenKelasPage> {

  final _currentIndex = 1;

  void _onNavTapped(int index) {
    if (index == _currentIndex) return;

    switch (index) {
      case 0:
        Navigator.pushReplacementNamed(context, '/dosen/dashboard');
        break;
      case 1:
        Navigator.pushReplacementNamed(context, '/dosen/kelas');
        break;
      case 2:
        Navigator.pushReplacementNamed(context, '/dosen/profile');
        break;
    }
  }

  // 🔥 dummy data (nanti ganti API)
  final List<Map<String, String>> courses = [
    {
      "name": "Pemrograman Web",
      "code": "TI-301",
      "semester": "Genap 2025/2026"
    },
    {
      "name": "Jaringan Komputer",
      "code": "TI-304",
      "semester": "Genap 2025/2026"
    }
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      bottomNavigationBar: BottomNav(
        currentIndex: _currentIndex,
        onTap: _onNavTapped,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [

            // 🔵 HEADER
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(20, 60, 20, 30),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF7C3AED), Color(0xFF6366F1)],
                ),
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(30),
                  bottomRight: Radius.circular(30),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      GestureDetector(
                        onTap: () {
                          Navigator.pushReplacementNamed(context, '/dosen/dashboard');
                        },
                        child: Container(
                          width: 40,
                          height: 40,
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.2),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(Icons.arrow_back, color: Colors.white),
                        ),
                      ),
                      const SizedBox(width: 12),
                      const Text(
                        "Kelas Saya",
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 22,
                          fontWeight: FontWeight.bold,
                        ),
                      )
                    ],
                  ),
                  const SizedBox(height: 10),
                  Text(
                    "${courses.length} kelas aktif semester ini",
                    style: const TextStyle(color: Colors.white70),
                  )
                ],
              ),
            ),

            const SizedBox(height: 16),

            // 🔥 LIST KELAS
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: courses.map((course) {
                  return _kelasCard(course);
                }).toList(),
              ),
            ),

            const SizedBox(height: 80),
          ],
        ),
      ),
    );
  }

  // 🔹 CARD KELAS
  Widget _kelasCard(Map<String, String> course) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 10,
          )
        ],
      ),
      child: Column(
        children: [
          // GestureDetector(
          //   onTap: () => Navigator.pushNamed(context, '/dosen/kelas/detail'),
          // ),
          // HEADER
          Row(
            children: [
              Container(
                width: 60,
                height: 60,
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    colors: [Color(0xFF7C3AED), Color(0xFF6366F1)],
                  ),
                  borderRadius: BorderRadius.all(Radius.circular(16)),
                ),
                child: const Icon(Icons.menu_book, color: Colors.white),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      course['name']!,
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                      ),
                    ),
                    Text(
                      "${course['code']} • ${course['semester']}",
                      style: const TextStyle(color: Colors.grey),
                    ),
                  ],
                ),
              )
            ],
          ),

          const SizedBox(height: 16),

          // STATS
          Row(
            children: [
              Expanded(child: _statBox("32", "Mahasiswa", Colors.purple)),
              const SizedBox(width: 10),
              Expanded(child: _statBox("14", "Pertemuan", Colors.blue)),
              const SizedBox(width: 10),
              Expanded(child: _statBox("7", "Selesai", Colors.green)),
            ],
          ),

          const SizedBox(height: 16),
          const Divider(),

          // ACTION
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: const [
              Text("Lihat detail & mulai sesi"),
              Icon(Icons.arrow_forward, color: Colors.purple),
            ],
          )
        ],
      ),
    );
  }

  // 🔹 STAT BOX
  Widget _statBox(String value, String label, Color color) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(14),
      ),
      child: Column(
        children: [
          Text(
            value,
            style: const TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 18,
            ),
          ),
          Text(label, style: const TextStyle(fontSize: 12))
        ],
      ),
    );
  }
}